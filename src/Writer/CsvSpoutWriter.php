<?php

namespace Kriss\DataExporter\Writer;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterInterface;
use Kriss\DataExporter\Writer\Traits\CsvTypedTrait;

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
