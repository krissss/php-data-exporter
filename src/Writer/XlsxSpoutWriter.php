<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;

class XlsxSpoutWriter extends BaseSpoutWriter
{
    use XlsxTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createXLSXWriter();
    }
}
