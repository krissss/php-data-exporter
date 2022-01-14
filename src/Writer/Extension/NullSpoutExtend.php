<?php

namespace Kriss\DataExporter\Writer\Extension;

use Box\Spout\Common\Entity\Style\Style;
use Box\Spout\Writer\WriterInterface;

class NullSpoutExtend implements SpoutExtendInterface
{
    /**
     * @inheritDoc
     */
    public function beforeOpen(WriterInterface $writer)
    {
    }

    /**
     * @inheritDoc
     */
    public function buildCellStyle($colIndex, $rowIndex): ?Style
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function buildRowStyle($rowIndex): ?Style
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function beforeClose(WriterInterface $writer): void
    {
    }
}
