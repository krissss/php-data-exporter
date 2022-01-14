<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class XlsxSpreadsheetWriter extends BaseSpreadsheetWriter
{
    use XlsxTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }
}
