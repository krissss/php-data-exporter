<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Expression\TypedExpression;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Interfaces\TypedExpressionSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\XLSX\Writer;

class XlsxSpoutWriter extends BaseSpoutWriter implements ExcelSheetSupportInterface, TypedExpressionSupportInterface
{
    use XlsxTypedTrait;
    use ExcelSheetSpoutTrait;

    protected function getWriter(): WriterInterface
    {
        return new Writer();
    }

    /**
     * @inheritDoc
     */
    protected function createCell($cellValue, ?Style $cellStyle): Cell
    {
        [, $dataValue] = $this->parseData($cellValue);

        $cell = parent::createCell($dataValue, $cellStyle);
        //$cell->setType($dataType);

        return $cell;
    }

    private function parseData($value): array
    {
        $typed = TypedExpression::fromValue($value);

        return [null, $typed->getRawValue()];
    }
}
