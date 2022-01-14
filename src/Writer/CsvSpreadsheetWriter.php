<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Traits\CsvTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class CsvSpreadsheetWriter extends BaseSpreadsheetWriter
{
    use CsvTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Csv');
    }
}
