<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\XLSX\Writer as XLSXWriter;
use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;
use Kriss\DataExporter\Writer\Extension\SpoutExtendInterface;
use Sonata\Exporter\Writer\TypedWriterInterface;

abstract class BaseSpoutWriter implements TypedWriterInterface
{
    private $filename;
    private $showHeaders;
    private $extend;

    /**
     * @var WriterInterface
     */
    private $writer;

    public function __construct(string $filename, bool $showHeaders = true, ?SpoutExtendInterface $extend = null)
    {
        if (! interface_exists('Box\Spout\Writer\WriterInterface')) {
            throw new \InvalidArgumentException('must install `box/spout` first');
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->extend = $extend ?: new NullSpoutExtend();
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
        if ($this->writer instanceof XLSXWriter && method_exists($this->writer, 'setDefaultRowHeight')) {
            /**
             * This PR is not merged
             * @link https://github.com/box/spout/pull/715
             */
            $this->writer->setDefaultRowHeight(15);
        }
        $this->extend->beforeOpen($this->writer);
        $this->writer->openToFile($this->filename);
    }

    private $row = 1;

    /**
     * @inheritDoc
     */
    public function write(array $data)
    {
        if ($this->showHeaders && $this->row === 1) {
            $this->writeRow(array_keys($data));
        }
        $this->writeRow($data);
    }

    private function writeRow(array $data)
    {
        $cells = [];
        foreach ($data as $index => $cellValue) {
            $cells[] = WriterEntityFactory::createCell($cellValue, $this->extend->buildCellStyle($index, $this->row));
        }
        $this->writer->addRow(WriterEntityFactory::createRow($cells, $this->extend->buildRowStyle($this->row)));

        $this->row++;
    }

    public function close()
    {
        $this->extend->beforeClose($this->writer);
        $this->writer->close();
    }
}
