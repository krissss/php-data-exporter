<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpreadsheetTrait;
use Kriss\DataExporter\Writer\Traits\XlsTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class XlsSpreadsheetWriter extends BaseSpreadsheetWriter implements ExcelSheetSupportInterface
{
    use XlsTypedTrait;
    use ExcelSheetSpreadsheetTrait;


    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Xls');
    }
}
