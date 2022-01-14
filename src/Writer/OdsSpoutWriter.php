<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
use Kriss\DataExporter\Writer\Traits\OdsTypedTrait;

class OdsSpoutWriter extends BaseSpoutWriter
{
    use OdsTypedTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createODSWriter();
    }
}
