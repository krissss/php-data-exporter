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

    protected $filename;
    protected $extend;

    /**
     * @var IWriter
     */
    protected $writer;

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
    protected $spreadsheet;

    /**
     * @inheritDoc
     */
    public function open()
    {
        $this->spreadsheet = new Spreadsheet();
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

    protected function writeRow(array $data)
    {
        $this->spreadsheet->getActiveSheet()->fromArray($data, null, 'A' . $this->row, true);
    }

    public function close()
    {
        $writer = $this->getWriter($this->spreadsheet);
        $this->extend->beforeWrite($this->spreadsheet, $writer);
        $writer->save($this->filename);
    }
}
