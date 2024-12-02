<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Style\Style;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\XLSX\Writer;
use Kriss\DataExporter\Writer\Expression\TypedExpression;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Interfaces\TypedExpressionSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;

class XlsxSpoutWriter extends BaseSpoutWriter implements ExcelSheetSupportInterface, TypedExpressionSupportInterface
{
    use XlsxTypedTrait;
    use ExcelSheetSpoutTrait;

    /**
     * @var Writer
     */
    protected $writer;

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createXLSXWriter();
    }

    /**
     * @inheritDoc
     */
    protected function createCell($cellValue, ?Style $cellStyle): Cell
    {
        [$dataType, $dataValue] = $this->parseData($cellValue);

        $cell = parent::createCell($dataValue, $cellStyle);
        $cell->setType($dataType);

        return $cell;
    }

    private function parseData($value): array
    {
        $typed = TypedExpression::fromValue($value);

        return [$typed->getSpoutType(), $typed->getRawValue()];
    }
}
