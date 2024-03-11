<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\OdsTypedTrait;
use OpenSpout\Writer\ODS\Writer;
use OpenSpout\Writer\WriterInterface;

class OdsSpoutWriter extends BaseSpoutWriter implements ExcelSheetSupportInterface
{
    use OdsTypedTrait;
    use ExcelSheetSpoutTrait;

    protected function getWriter(): WriterInterface
    {
        return new Writer();
    }
}
