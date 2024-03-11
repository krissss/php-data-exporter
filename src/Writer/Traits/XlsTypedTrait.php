<?php

namespace Kriss\DataExporter\Writer\Traits;

trait XlsTypedTrait
{
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.ms-excel';
    }

    public function getFormat(): string
    {
        return 'xls';
    }
}
