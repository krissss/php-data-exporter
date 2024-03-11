<?php

namespace Kriss\DataExporter\Writer\Extension;

use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;

class NullSpoutExtend implements SpoutExtendInterface
{
    public function beforeOpen(WriterInterface $writer): void
    {
    }

    public function buildCellStyle(int|string $colIndex, int $rowIndex): ?Style
    {
        return null;
    }

    public function buildRowStyle(int $rowIndex): ?Style
    {
        return null;
    }

    public function beforeClose(WriterInterface $writer): void
    {
    }
}
