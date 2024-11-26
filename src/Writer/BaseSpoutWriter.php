<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;
use Kriss\DataExporter\Writer\Extension\SpoutExtendInterface;
use Kriss\DataExporter\Writer\Traits\ShowHeaderTrait;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
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
            $cellStyle = $this->extend->buildCellStyleWithContext($index, $this->row, [
                'cell_value' => $cellValue,
                'row_data' => $data,
            ]);
            $cell = $this->createCell($cellValue, $cellStyle);
            $cell = $this->extend->buildCell($index, $this->row, $cell, [
                'row_data' => $data,
            ]);
            $cells[] = $cell;
        }
        $rowStyle = $this->extend->buildRowStyleWithContext($this->row, [
            'row_data' => $data,
        ]);
        $row = $this->createRow($cells, $rowStyle);
        $row = $this->extend->buildRow($this->row, $row, [
            'row_data' => $data,
        ]);
        $this->writer->addRow($row);
    }

    /**
     * @param mixed $cellValue
     * @param Style|null $cellStyle
     * @return Cell
     */
    protected function createCell($cellValue, ?Style $cellStyle): Cell
    {
        return Cell::fromValue($cellValue, $cellStyle);
    }

    /**
     * @param Cell[] $cells
     * @param Style|null $rowStyle
     * @return Row
     */
    protected function createRow(array $cells, ?Style $rowStyle): Row
    {
        return new Row($cells, $rowStyle);
    }

    public function close(): void
    {
        $this->extend->beforeClose($this->writer);
        $this->writer->close();
    }
}
