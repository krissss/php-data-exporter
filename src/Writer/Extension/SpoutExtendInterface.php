<?php

namespace Kriss\DataExporter\Writer\Extension;

use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;

interface SpoutExtendInterface
{
    /**
     * 打开文件前
     * @link https://github.com/openspout/openspout/blob/4.x/docs/documentation.md
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeOpen(WriterInterface $writer): void;

    /**
     * 构建单个 cell 的样式
     * @link https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#styling-cells
     * @param int|string $colIndex 数据源中的数组 index
     * @param int $rowIndex 行号，从1开始
     * @param array{cell_value: string|int|float|mixed, row_data: array} $context
     * @return Style|null
     */
    public function buildCellStyleWithContext(int|string $colIndex, int $rowIndex, array $context = []): ?Style;

    /**
     * 处理 cell
     * @param $colIndex
     * @param int $rowIndex
     * @param Cell $cell
     * @param array $context
     * @return Cell
     */
    public function buildCell($colIndex, int $rowIndex, Cell $cell, array $context = []): Cell;

    /**
     * 构建单个 row 的样式
     * @link https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#styling-rows
     * @param int $rowIndex 行号，从1开始
     * @param array{row_data: array} $context
     * @return Style|null
     */
    public function buildRowStyleWithContext(int $rowIndex, array $context = []): ?Style;

    /**
     * 处理 cell
     * @param int $rowIndex
     * @param Row $row
     * @param array $context
     * @return Row
     */
    public function buildRow(int $rowIndex, Row $row, array $context = []): Row;

    /**
     * 关闭前
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeClose(WriterInterface $writer): void;
}
