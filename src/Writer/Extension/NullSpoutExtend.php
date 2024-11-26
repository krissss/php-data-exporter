<?php

namespace Kriss\DataExporter\Writer\Extension;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Row;
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
     * @deprecated use buildCellStyleWithContext instead
     * @param $colIndex
     * @param $rowIndex
     * @return Style|null
     */
    public function buildCellStyle($colIndex, $rowIndex): ?Style
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function buildCellStyleWithContext($colIndex, int $rowIndex, array $context): ?Style
    {
        return $this->buildCellStyle($colIndex, $rowIndex);
    }

    /**
     * @inheritDoc
     */
    public function buildCell($colIndex, int $rowIndex, Cell $cell, array $context): Cell
    {
        return $cell;
    }

    /**
     * @deprecated use buildRowStyleWithContext instead
     * @param $rowIndex
     * @return Style|null
     */
    public function buildRowStyle($rowIndex): ?Style
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function buildRowStyleWithContext(int $rowIndex, array $context): ?Style
    {
        return $this->buildRowStyle($rowIndex);
    }

    /**
     * @inheritDoc
     */
    public function buildRow(int $rowIndex, Row $row, array $context): Row
    {
        return $row;
    }

    /**
     * @inheritDoc
     */
    public function beforeClose(WriterInterface $writer): void
    {
    }
}
