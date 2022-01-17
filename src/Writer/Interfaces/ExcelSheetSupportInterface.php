<?php

namespace Kriss\DataExporter\Writer\Interfaces;

interface ExcelSheetSupportInterface
{
    /**
     * 设置并激活 sheet
     * @param int|string $sheet
     */
    public function setActiveSheet($sheet): void;
}
