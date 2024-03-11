<?php

namespace Kriss\DataExporter\Writer\Traits;

trait CsvTypedTrait
{
    public function getDefaultMimeType(): string
    {
        return 'text/csv';
    }

    public function getFormat(): string
    {
        return 'csv';
    }
}
