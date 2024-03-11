<?php

namespace Kriss\DataExporter\Writer\Traits;

use OpenSpout\Writer\AbstractWriterMultiSheets;

trait ExcelSheetSpoutTrait
{
    protected bool $isRemovedDefaultSheet = false;

    public function setActiveSheet(int|string $sheet): void
    {
        if (! $this->writer instanceof AbstractWriterMultiSheets) {
            throw new \InvalidArgumentException('writer must be AbstractWriterMultiSheets');
        }
        if (is_int($sheet)) {
            if ($sheet === 0) {
                return;
            }
            $this->writer->addNewSheetAndMakeItCurrent();
            $this->resetRow();

            return;
        }
        if ($this->isRemovedDefaultSheet === false) {
            $this->writer->getCurrentSheet()->setName($sheet);
            $this->isRemovedDefaultSheet = true;
            $this->resetRow();

            return;
        }
        $this->writer->addNewSheetAndMakeItCurrent();
        $this->writer->getCurrentSheet()->setName($sheet);
        $this->resetRow();
    }
}
