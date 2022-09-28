<?php

namespace Kriss\DataExporter\Writer\Extension;

use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;

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
     * @param $colIndex
     * @param $rowIndex
     * @return Style|null
     */
    public function buildCellStyle($colIndex, $rowIndex): ?Style;

    /**
     * @link https://opensource.box.com/spout/docs/#styling
     * @param $rowIndex
     * @return Style|null
     */
    public function buildRowStyle($rowIndex): ?Style;

    /**
     * @param WriterInterface $writer
     * @return void
     */
    public function beforeClose(WriterInterface $writer): void;
}
