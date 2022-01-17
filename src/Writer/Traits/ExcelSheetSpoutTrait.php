<?php

namespace Kriss\DataExporter\Writer\Traits;

trait ExcelSheetSpoutTrait
{
    protected $isRemovedDefaultSheet = false;

    /**
     * @inheritDoc
     */
    public function setActiveSheet($sheet): void
    {
        if (is_int($sheet)) {
            if ($sheet === 0) {
                return;
            }
            $this->writer->addNewSheetAndMakeItCurrent();

            return;
        }
        if ($this->isRemovedDefaultSheet === false) {
            $this->writer->getCurrentSheet()->setName($sheet);
            $this->isRemovedDefaultSheet = true;

            return;
        }
        $this->writer->addNewSheetAndMakeItCurrent();
        $this->writer->getCurrentSheet()->setName($sheet);
    }
}
