<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Expression\TypedExpression;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Interfaces\TypedExpressionSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpreadsheetTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class XlsxSpreadsheetWriter extends BaseSpreadsheetWriter implements ExcelSheetSupportInterface, TypedExpressionSupportInterface
{
    use XlsxTypedTrait;
    use ExcelSheetSpreadsheetTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(Spreadsheet $spreadsheet): IWriter
    {
        return IOFactory::createWriter($spreadsheet, 'Xlsx');
    }

    protected function writeRow(array $data)
    {
        $column = 1;
        foreach ($data as $value) {
            [$dataType, $dataValue] = $this->parseData($value);

            $this->spreadsheet->getActiveSheet()->setCellValueExplicit(Coordinate::stringFromColumnIndex($column) . $this->row, $dataValue, $dataType);

            ++$column;
        }
    }

    private function parseData($value): array
    {
        $typed = TypedExpression::fromValue($value);

        return [$typed->getSpreadsheetType(), $typed->getValue()];
    }
}
