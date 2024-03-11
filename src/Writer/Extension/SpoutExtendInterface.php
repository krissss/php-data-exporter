<?php

namespace Kriss\DataExporter\Writer\Extension;

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
     * @param int|string $colIndex 列数据的 index
     * @param int $rowIndex start from 1
     * @return Style|null
     * @link https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#styling-cells
     * @deprecated  下个版本移除，使用
     */
    public function buildCellStyle(int|string $colIndex, int $rowIndex): ?Style;

    /**
     * 构建单个 row 的样式
     * @param int $rowIndex start from 1
     * @return Style|null
     * @deprecated 下个版本移除，使用
     * @link https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#styling-rows
     */
    public function buildRowStyle(int $rowIndex): ?Style;

    /**
     * 关闭前
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeClose(WriterInterface $writer): void;
}
