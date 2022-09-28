<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Traits\CsvTypedTrait;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterInterface;

class CsvSpoutWriter extends BaseSpoutWriter
{
    use CsvTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createCSVWriter();
    }
}
