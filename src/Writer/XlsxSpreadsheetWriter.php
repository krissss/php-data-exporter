<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpreadsheetTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class XlsxSpreadsheetWriter extends BaseSpreadsheetWriter implements ExcelSheetSupportInterface
{
    use XlsxTypedTrait;
    use ExcelSheetSpreadsheetTrait;

    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }
}
