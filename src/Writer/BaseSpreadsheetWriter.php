<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Extension\NullSpreadsheetExtend;
use Kriss\DataExporter\Writer\Extension\SpreadsheetExtendInterface;
use Kriss\DataExporter\Writer\Traits\ShowHeaderTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;

abstract class BaseSpreadsheetWriter implements TypedWriterInterface
{
    use ShowHeaderTrait;

    protected string $filename;
    protected SpreadsheetExtendInterface $extend;

    protected IWriter $writer;

    final public function __construct(string $filename, ?bool $showHeaders = null, ?SpreadsheetExtendInterface $extend = null)
    {
        if (! interface_exists('PhpOffice\PhpSpreadsheet\Writer\IWriter')) {
            throw new \InvalidArgumentException('must install `phpoffice/phpspreadsheet` first');
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->extend = $extend ?: new NullSpreadsheetExtend();
    }

    abstract protected function getWriter(Spreadsheet $spreadsheet): IWriter;

    protected Spreadsheet $spreadsheet;

    public function open(): void
    {
        $this->spreadsheet = new Spreadsheet();
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

    protected function writeRow(array $data): void
    {
        $this->spreadsheet->getActiveSheet()->fromArray($data, null, 'A' . $this->row, true);
    }

    protected function resetRow(): void
    {
        $this->row = 1;
    }

    public function close(): void
    {
        $this->spreadsheet->setActiveSheetIndex(0); // reset active sheet
        $writer = $this->getWriter($this->spreadsheet);
        $this->extend->beforeWrite($this->spreadsheet, $writer);
        $writer->save($this->filename);
    }
}
