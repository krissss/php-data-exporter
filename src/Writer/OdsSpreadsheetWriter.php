<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Traits\OdsTypedTrait;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class OdsSpreadsheetWriter extends BaseSpreadsheetWriter
{
    use OdsTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Ods');
    }
}
