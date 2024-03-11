<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Traits\CsvTypedTrait;
use OpenSpout\Writer\CSV\Writer;
use OpenSpout\Writer\WriterInterface;

class CsvSpoutWriter extends BaseSpoutWriter
{
    use CsvTypedTrait;

    protected function getWriter(): WriterInterface
    {
        return new Writer();
    }
}
