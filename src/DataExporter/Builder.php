<?php

namespace Kriss\DataExporter\DataExporter;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use Iterator;
use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Source\ExcelSheetSourceIterator;
use Kriss\DataExporter\Source\GeneratorChainSourceIterator;
use Kriss\DataExporter\Traits\ObjectEventsSupportTrait;
use Kriss\DataExporter\Writer\Expression\TypedExpression;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Interfaces\TypedExpressionSupportInterface;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Writer\WriterInterface;

class Builder
{
    use ObjectEventsSupportTrait;

    public const EVENT_AFTER_OPEN = 'afterOpen';
    public const EVENT_BEFORE_CLOSE = 'beforeClose';
    public const EVENT_BEFORE_EACH_ROW_WRITE = 'beforeEchoRowWrite';
    public const EVENT_AFTER_EACH_ROW_WRITE = 'afterEachRowWrite';

    /**
     * @deprecated replace with EVENT_BEFORE_ECHO_ROW_WRITE
     */
    public const EVENT_BEFORE_ECHO_ROW_WRITE = self::EVENT_BEFORE_EACH_ROW_WRITE;
    /**
     * @deprecated replace with EVENT_AFTER_ECHO_ROW_WRITE
     */
    public const EVENT_AFTER_ECHO_ROW_WRITE = self::EVENT_AFTER_EACH_ROW_WRITE;

    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var Iterator|GeneratorChainSourceIterator
     */
    private $source;

    /**
     * @param WriterInterface $writer
     * @return $this
     */
    public function withWriter(WriterInterface $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * @param $source
     * @return $this
     */
    public function withSource($source): self
    {
        if ($source instanceof Arrayable) {
            $source = $source->toArray();
        }
        if (is_array($source)) {
            $source = new ArraySourceIterator($source);
        }
        if (! $source instanceof Iterator) {
            throw new InvalidArgumentException('Not support $source.');
        }

        $this->source = $source;

        return $this;
    }

    /**
     * @return WriterInterface|null
     */
    public function getWriter(): ?WriterInterface
    {
        return $this->writer;
    }

    /**
     * @return Iterator|null
     */
    public function getSource(): ?Iterator
    {
        return $this->source;
    }

    /**
     * @see \Sonata\Exporter\Handler::export()
     */
    public function export(): void
    {
        $this->writer->open();
        $this->handleEvent(self::EVENT_AFTER_OPEN, $this);

        $index = 0;
        $lastSheet = null;
        foreach ($this->parseSource($this->source) as $key => $source) {
            if ($this->writer instanceof ExcelSheetSupportInterface && $lastSheet !== $key) {
                $this->writer->setActiveSheet($key);
                $lastSheet = $key; // 相同 sheet 名不重复设置
            }
            foreach ($source as $data) {
                $this->handleEvent(self::EVENT_BEFORE_EACH_ROW_WRITE, $data, $index, $this);
                $data = $this->prepareData($this->writer, $data);
                $this->writer->write($data);
                $this->handleEvent(self::EVENT_AFTER_EACH_ROW_WRITE, $data, $index, $this);
                $index++;
            }
        }

        $this->handleEvent(self::EVENT_BEFORE_CLOSE, $this);
        $this->writer->close();
    }

    private function parseSource(Iterator $source): \Generator
    {
        if ($source instanceof ExcelSheetSourceIterator) {
            foreach ($source as $sheet => $deepSource) {
                foreach ($this->loopGeneratorChainSource($deepSource) as $value) {
                    yield $sheet => $value;
                }
            }

            return;
        }
        foreach ($this->loopGeneratorChainSource($source) as $value) {
            yield 0 => $value;
        }
    }

    private function loopGeneratorChainSource(Iterator $source): \Generator
    {
        if ($source instanceof GeneratorChainSourceIterator) {
            foreach ($source as $newSource) {
                foreach ($this->loopGeneratorChainSource($newSource) as $value) {
                    yield $value;
                }
            }

            return;
        }
        yield $source;
    }

    private function prepareData(WriterInterface $writer, array $data)
    {
        if (! $writer instanceof TypedExpressionSupportInterface) {
            return array_map(function ($value) {
                // 不支持 TypedExpression 的 writer 直接返回原始数据
                if ($value instanceof TypedExpression) {
                    return $value->getRawValue();
                }
                // 日期转字符串
                if ($value instanceof \DateTimeInterface) {
                    return $value->format(DataExporter::$defaultDateTimeFormat);
                }

                return $value;
            }, $data);
        }

        return $data;
    }
}
