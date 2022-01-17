<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpreadsheetTrait;
use Kriss\DataExporter\Writer\Traits\OdsTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class OdsSpreadsheetWriter extends BaseSpreadsheetWriter implements ExcelSheetSupportInterface
{
    use OdsTypedTrait;
    use ExcelSheetSpreadsheetTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Ods');
    }
}
