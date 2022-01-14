<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
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
