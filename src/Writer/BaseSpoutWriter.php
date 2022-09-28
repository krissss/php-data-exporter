<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;
use Kriss\DataExporter\Writer\Extension\SpoutExtendInterface;
use Kriss\DataExporter\Writer\Traits\ShowHeaderTrait;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\WriterMultiSheetsAbstract;
use Sonata\Exporter\Writer\TypedWriterInterface;

abstract class BaseSpoutWriter implements TypedWriterInterface
{
    use ShowHeaderTrait;

    protected $filename;
    protected $extend;

    /**
     * @var WriterInterface
     */
    protected $writer;

    public function __construct(string $filename, ?bool $showHeaders = null, ?SpoutExtendInterface $extend = null)
    {
        if (! interface_exists('OpenSpout\Writer\WriterInterface')) {
            throw new \InvalidArgumentException('must install `openspout/openspout` first');
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
        if ($this->writer instanceof WriterMultiSheetsAbstract) {
            $this->writer->setDefaultRowHeight(15);
        }
        $this->extend->beforeOpen($this->writer);
        $this->writer->openToFile($this->filename);
    }

    protected $row = 1;

    /**
     * @inheritDoc
     */
    public function write(array $data)
    {
        if ($this->row === 1 && $this->shouldAddHeader($data)) {
            $this->writeRow(array_keys($data));
            $this->row++;
        }
        $this->writeRow($data);
        $this->row++;
    }

    protected function resetRow(): void
    {
        $this->row = 1;
    }

    protected function writeRow(array $data)
    {
        $cells = [];
        foreach ($data as $index => $cellValue) {
            $cell = WriterEntityFactory::createCell($cellValue, $this->extend->buildCellStyle($index, $this->row));
            $this->extend->afterCellCreate($index, $this->row, $cell);
            $cells[] = $cell;
        }
        $row = WriterEntityFactory::createRow($cells, $this->extend->buildRowStyle($this->row));
        $this->extend->afterRowCreate($this->row, $row);

        $this->writer->addRow($row);
    }

    public function close()
    {
        $this->extend->beforeClose($this->writer);
        $this->writer->close();
    }
}
