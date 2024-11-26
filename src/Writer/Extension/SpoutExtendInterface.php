<?php

namespace Kriss\DataExporter\Writer\Extension;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Entity\Style\Style;
use Box\Spout\Writer\WriterInterface;

interface SpoutExtendInterface
{
    /**
     * @link https://opensource.box.com/spout/docs/
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeOpen(WriterInterface $writer);

    /**
     * @link https://opensource.box.com/spout/docs/#styling
     * @param int|string $colIndex 数据源中的数组 index
     * @param int $rowIndex 行号，从1开始
     * @param array{cell_value: string|int|float, row_data: array} $context
     * @return Style|null
     */
    public function buildCellStyleWithContext($colIndex, int $rowIndex, array $context): ?Style;

    /**
     * 处理 cell
     * @param $colIndex
     * @param int $rowIndex
     * @param Cell $cell
     * @param array{row_data: array} $context
     * @return Cell
     */
    public function buildCell($colIndex, int $rowIndex, Cell $cell, array $context): Cell;

    /**
     * @link https://opensource.box.com/spout/docs/#styling
     * @param int $rowIndex 行号，从1开始
     * @param array{row_data: array} $context
     * @return Style|null
     */
    public function buildRowStyleWithContext(int $rowIndex, array $context): ?Style;

    /**
     * 处理 cell
     * @param int $rowIndex
     * @param Row $row
     * @param array{row_data: array} $context
     * @return Row
     */
    public function buildRow(int $rowIndex, Row $row, array $context): Row;

    /**
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeClose(WriterInterface $writer): void;
}
