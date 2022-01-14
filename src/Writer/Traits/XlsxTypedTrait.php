<?php

namespace Kriss\DataExporter\Writer\Traits;

trait XlsxTypedTrait
{
    /**
     * @inheritDoc
     */
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return 'xlsx';
    }
}
