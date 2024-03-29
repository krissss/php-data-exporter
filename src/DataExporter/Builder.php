<?php

namespace Kriss\DataExporter\DataExporter;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use Iterator;
use Kriss\DataExporter\Source\ExcelSheetSourceIterator;
use Kriss\DataExporter\Source\GeneratorChainSourceIterator;
use Kriss\DataExporter\Traits\ObjectEventsSupportTrait;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Writer\WriterInterface;

class Builder
{
    use ObjectEventsSupportTrait;

    public const EVENT_AFTER_OPEN = 'afterOpen'; // 打开文件后
    public const EVENT_BEFORE_CLOSE = 'beforeClose'; // 关闭前
    public const EVENT_BEFORE_EACH_ROW_WRITE = 'beforeEachRowWrite'; // 每一行写入前
    public const EVENT_AFTER_EACH_ROW_WRITE = 'afterEachRowWrite'; // 每一行写入后

    private WriterInterface $writer;
    private GeneratorChainSourceIterator|Iterator $source;

    /**
     * @param WriterInterface $writer
     * @return $this
     */
    public function withWriter(WriterInterface $writer): static
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * @param $source
     * @return $this
     */
    public function withSource($source): static
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
        $this->handleEvent(static::EVENT_AFTER_OPEN, $this);

        $index = 0;
        $lastSheet = null;
        foreach ($this->parseSource($this->source) as $key => $source) {
            if ($this->writer instanceof ExcelSheetSupportInterface && $lastSheet !== $key) {
                $this->writer->setActiveSheet($key);
                $lastSheet = $key; // 相同 sheet 名不重复设置
            }
            foreach ($source as $data) {
                $this->handleEvent(static::EVENT_BEFORE_EACH_ROW_WRITE, $data, $index, $this);
                $this->writer->write($data);
                $this->handleEvent(static::EVENT_AFTER_EACH_ROW_WRITE, $data, $index, $this);
                $index++;
            }
        }

        $this->handleEvent(static::EVENT_BEFORE_CLOSE, $this);
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
}
