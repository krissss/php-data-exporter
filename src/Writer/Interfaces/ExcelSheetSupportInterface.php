<?php

namespace Kriss\DataExporter\Writer\Interfaces;

interface ExcelSheetSupportInterface
{
    /**
     * 设置并激活 sheet
     */
    public function setActiveSheet(int|string $sheet): void;
}
