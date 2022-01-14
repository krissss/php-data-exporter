<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Extension\NullSpreadsheetExtend;
use Kriss\DataExporter\Writer\Extension\SpreadsheetExtendInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Sonata\Exporter\Writer\TypedWriterInterface;

abstract class BaseSpreadsheetWriter implements TypedWriterInterface
{
    private $filename;
    private $showHeaders;
    private $extend;

    /**
     * @var IWriter
     */
    private $writer;

    public function __construct(string $filename, bool $showHeaders = true, ?SpreadsheetExtendInterface $extend = null)
    {
        if (! interface_exists('PhpOffice\PhpSpreadsheet\Writer\IWriter')) {
            throw new \InvalidArgumentException('must install `phpoffice/phpspreadsheet` first');
        }

        $this->filename = $filename;
        $this->showHeaders = $showHeaders;
        $this->extend = $extend ?: new NullSpreadsheetExtend();
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @return IWriter
     */
    abstract protected function getWriter(Spreadsheet $spreadsheet): IWriter;

    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    public function getSpreadsheet(): ?Spreadsheet
    {
        return $this->spreadsheet;
    }

    /**
     * @inheritDoc
     */
    public function open()
    {
        $this->spreadsheet = new Spreadsheet();
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
        $this->spreadsheet->getActiveSheet()->fromArray($data, null, 'A' . $this->row, true);

        $this->row++;
    }

    public function close()
    {
        $writer = $this->getWriter($this->spreadsheet);
        $this->extend->beforeWrite($this->spreadsheet, $writer);
        $writer->save($this->filename);
    }
}
