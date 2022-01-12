<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;

class CsvSpoutWriter extends BaseSpoutWriter
{
    /**
     * @inheritDoc
     */
    public function getDefaultMimeType(): string
    {
        return 'text/csv';
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return 'csv';
    }

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createCSVWriter();
    }
}
