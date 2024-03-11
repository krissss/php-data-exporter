<?php

namespace Kriss\DataExporter\Writer\Traits;

trait OdsTypedTrait
{
    public function getDefaultMimeType(): string
    {
        return 'application/vnd.oasis.opendocument.spreadsheet';
    }

    public function getFormat(): string
    {
        return 'ods';
    }
}
