<?php

namespace Kriss\DataExporter\Writer\Traits;

trait XlsxTypedTrait
{
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    public function getFormat(): string
    {
        return 'xlsx';
    }
}
