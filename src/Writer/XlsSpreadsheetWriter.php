<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Traits\XlsTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class XlsSpreadsheetWriter extends BaseSpreadsheetWriter
{
    use XlsTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Xls');
    }
}
