<?php

namespace Kriss\DataExporter\Writer\Extension;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;

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
    public function afterCellCreate($colIndex, int $rowIndex, Cell $cell): void
    {
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
    public function afterRowCreate(int $rowIndex, Row $row): void
    {
    }

    /**
     * @inheritDoc
     */
    public function beforeClose(WriterInterface $writer): void
    {
    }
}
