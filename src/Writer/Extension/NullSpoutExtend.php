<?php

namespace Kriss\DataExporter\Writer\Extension;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
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

    public function buildCellStyleWithContext(int|string $colIndex, int $rowIndex, array $context = []): ?Style
    {
        return $this->buildCellStyle($colIndex, $rowIndex);
    }

    public function buildCell($colIndex, int $rowIndex, Cell $cell, array $context = []): Cell
    {
        return $cell;
    }

    public function buildRowStyle(int $rowIndex, array $context = []): ?Style
    {
        return null;
    }

    public function buildRowStyleWithContext(int $rowIndex, array $context = []): ?Style
    {
        return $this->buildRowStyle($rowIndex, $context);
    }

    /**
     * @inheritDoc
     */
    public function buildRow(int $rowIndex, Row $row, array $context = []): Row
    {
        return $row;
    }

    public function beforeClose(WriterInterface $writer): void
    {
    }
}
