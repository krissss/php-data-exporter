<?php

namespace Kriss\DataExporter\Writer\Extension;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class NullSpreadsheetExtend implements SpreadsheetExtendInterface
{
    public function beforeWrite(Spreadsheet $spreadsheet, IWriter $writer): void
    {
    }
}
