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
     * @link https://github.com/openspout/openspout/blob/3.x/docs/documentation.md
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeOpen(WriterInterface $writer);

    /**
     * 构建单个 cell 的样式
     * @param int $colIndex start from 0(A=0)
     * @param int $rowIndex start from 1
     * @return Style|null
     * @link https://github.com/openspout/openspout/blob/3.x/docs/documentation.md#styling-cells
     * @deprecated  下个版本移除，使用
     */
    public function buildCellStyle($colIndex, $rowIndex): ?Style;

    /**
     * cell 创建后，可以修改 cell 的样式等
     * @link https://github.com/openspout/openspout/blob/3.x/docs/documentation.md#styling-cells
     * @param int $colIndex start from 0(A=0)
     * @param int $rowIndex start from 1
     * @param Cell $cell
     */
    public function afterCellCreate(int $colIndex, int $rowIndex, Cell $cell): void;

    /**
     * 构建单个 row 的样式
     * @param int $rowIndex start from 1
     * @return Style|null
     * @deprecated 下个版本移除，使用
     * @link https://github.com/openspout/openspout/blob/3.x/docs/documentation.md#styling-rows
     */
    public function buildRowStyle($rowIndex): ?Style;

    /**
     * row 创建后，可以修改 row 的样式等
     * @link https://github.com/openspout/openspout/blob/3.x/docs/documentation.md#styling-rows
     * @param int $rowIndex start from 1
     * @param Row $row
     */
    public function afterRowCreate(int $rowIndex, Row $row): void;

    /**
     * 关闭前
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeClose(WriterInterface $writer): void;
}
