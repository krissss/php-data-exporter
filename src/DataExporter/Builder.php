<?php

namespace Kriss\DataExporter\DataExporter;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;
use Iterator;
use Kriss\DataExporter\Source\GeneratorChainSourceIterator;
use Kriss\DataExporter\Traits\ObjectEventsSupportTrait;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Writer\WriterInterface;

class Builder
{
    use ObjectEventsSupportTrait;

    public const EVENT_AFTER_OPEN = 'afterOpen';
    public const EVENT_BEFORE_CLOSE = 'beforeClose';
    public const EVENT_BEFORE_ECHO_ROW_WRITE = 'beforeEchoRowWrite';
    public const EVENT_AFTER_ECHO_ROW_WRITE = 'afterEchoRowWrite';

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
     * @return WriterInterface
     */
    public function getWriter(): WriterInterface
    {
        return $this->writer;
    }

    /**
     * @return Iterator
     */
    public function getSource(): Iterator
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
        foreach ($this->eachSource() as $source) {
            foreach ($source as $data) {
                $this->handleEvent(self::EVENT_BEFORE_ECHO_ROW_WRITE, $data, $index, $this);
                $this->writer->write($data);
                $this->handleEvent(self::EVENT_AFTER_ECHO_ROW_WRITE, $data, $index, $this);
                $index++;
            }
        }

        $this->handleEvent(self::EVENT_BEFORE_CLOSE, $this);
        $this->writer->close();
    }

    private function eachSource(): \Generator
    {
        if ($this->source instanceof GeneratorChainSourceIterator) {
            foreach ($this->source as $source) {
                yield $source;
            }
        } else {
            yield $this->source;
        }
    }
}
