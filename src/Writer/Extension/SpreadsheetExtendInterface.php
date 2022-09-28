<?php

namespace Kriss\DataExporter\Writer\Extension;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

interface SpreadsheetExtendInterface
{
    /**
     * 真正写入文件前
     * @link https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#styles
     * @param Spreadsheet $spreadsheet
     * @param IWriter $writer
     */
    public function beforeWrite(Spreadsheet $spreadsheet, IWriter $writer): void;
}
