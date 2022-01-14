<?php

namespace Kriss\DataExporter\Writer\Traits;

trait CsvTypedTrait
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
}
