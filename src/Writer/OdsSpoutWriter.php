<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;

class OdsSpoutWriter extends BaseSpoutWriter
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

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createODSWriter();
    }
}
