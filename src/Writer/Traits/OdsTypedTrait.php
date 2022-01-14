<?php

namespace Kriss\DataExporter\Writer\Traits;

trait OdsTypedTrait
{
    /**
     * @inheritDoc
     */
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.oasis.opendocument.spreadsheet';
    }

    /**
     * @inheritDoc
     */
    public function getFormat(): string
    {
        return 'ods';
    }
}
