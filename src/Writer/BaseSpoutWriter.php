<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;
use Kriss\DataExporter\Writer\Extension\SpoutExtendInterface;
use Kriss\DataExporter\Writer\Traits\ShowHeaderTrait;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\WriterInterface;
use Sonata\Exporter\Writer\TypedWriterInterface;

abstract class BaseSpoutWriter implements TypedWriterInterface
{
    use ShowHeaderTrait;

    protected string $filename;
    protected SpoutExtendInterface $extend;

    protected WriterInterface $writer;

    public function __construct(string $filename, ?bool $showHeaders = null, ?SpoutExtendInterface $extend = null)
    {
        if (! interface_exists('OpenSpout\Writer\WriterInterface')) {
            throw new \InvalidArgumentException('must install `openspout/openspout` first');
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->extend = $extend ?: new NullSpoutExtend();
    }

    abstract protected function getWriter(): WriterInterface;

    public function open(): void
    {
        $this->writer = $this->getWriter();
        $this->extend->beforeOpen($this->writer);
        $this->writer->openToFile($this->filename);
    }

    protected int $row = 1;

    public function write(array $data): void
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

    protected function writeRow(array $data): void
    {
        $cells = [];
        foreach ($data as $index => $cellValue) {
            $cell = Cell::fromValue($cellValue, $this->extend->buildCellStyle($index, $this->row));
            $cells[] = $cell;
        }
        $row = new Row($cells, $this->extend->buildRowStyle($this->row));

        $this->writer->addRow($row);
    }

    public function close(): void
    {
        $this->extend->beforeClose($this->writer);
        $this->writer->close();
    }
}
