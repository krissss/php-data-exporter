<?php

namespace Kriss\DataExporter\Writer\Traits;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ExcelSheetSpreadsheetTrait
{
    protected $isRemovedDefaultSheet = false;

    /**
     * @inheritDoc
     */
    public function setActiveSheet($sheet): void
    {
        if (is_int($sheet)) {
            if ($sheet === 0) {
                $this->spreadsheet->setActiveSheetIndex($sheet);
                $this->resetRow();
                return;
            }
            $this->spreadsheet->createSheet($sheet);
            $this->spreadsheet->setActiveSheetIndex($sheet);
            $this->resetRow();
            return;
        }
        $worksheet = new Worksheet($this->spreadsheet, $sheet);
        if ($this->isRemovedDefaultSheet === false) {
            $this->spreadsheet->removeSheetByIndex(0);
            $this->isRemovedDefaultSheet = true;
        }
        $this->spreadsheet->addSheet($worksheet);
        $this->spreadsheet->setActiveSheetIndexByName($sheet);
        $this->resetRow();
    }
}
