<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;

class XlsxSpoutWriter extends BaseSpoutWriter
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

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createXLSXWriter();
    }
}
