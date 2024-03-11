<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\XLSX\Writer;

class XlsxSpoutWriter extends BaseSpoutWriter implements ExcelSheetSupportInterface
{
    use XlsxTypedTrait;
    use ExcelSheetSpoutTrait;

    protected function getWriter(): WriterInterface
    {
        return new Writer();
    }
}
