<?php

namespace Kriss\DataExporter\Writer\Traits;

trait XlsTypedTrait
{
    /**
     * @inheritDoc
     */
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.ms-excel';
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return 'xls';
    }
}
