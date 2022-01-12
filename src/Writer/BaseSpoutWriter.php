<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\XLSX\Writer as XLSXWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;

abstract class BaseSpoutWriter implements TypedWriterInterface
{
    private $filename;
    private $showHeaders;
    private $writerConfigCb;

    /**
     * @var WriterInterface
     */
    private $writer;

    public function __construct(string $filename, bool $showHeaders = true, callable $writerConfigCb = null)
    {
        if (! interface_exists('Box\Spout\Writer\WriterInterface')) {
            throw new \InvalidArgumentException('must install `box/spout` first');
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->writerConfigCb = $writerConfigCb;
    }

    /**
     * @return WriterInterface
     */
    abstract protected function getWriter(): WriterInterface;

    /**
     * @inheritDoc
     */
    public function open()
    {
        $this->writer = $this->getWriter();
        if ($this->writer instanceof XLSXWriter) {
            $this->writer->setDefaultRowHeight(15);
        }
        if ($this->writerConfigCb && is_callable($this->writerConfigCb)) {
            $this->writer = call_user_func($this->writerConfigCb, $this->writer);
        }
        $this->writer->openToFile($this->filename);
    }

    private $isWriteHeader = false;

    /**
     * @inheritDoc
     */
    public function write(array $data)
    {
        if ($this->showHeaders && ! $this->isWriteHeader) {
            $this->writer->addRow(WriterEntityFactory::createRowFromArray(array_keys($data)));
            $this->isWriteHeader = true;
        }

        $this->writer->addRow(WriterEntityFactory::createRowFromArray($data));
    }

    public function close()
    {
        $this->writer->close();
    }
}
