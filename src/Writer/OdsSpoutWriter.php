<?php

namespace Kriss\DataExporter\Writer;

use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterInterface;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\OdsTypedTrait;

class OdsSpoutWriter extends BaseSpoutWriter implements ExcelSheetSupportInterface
{
    use OdsTypedTrait;
    use ExcelSheetSpoutTrait;

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createODSWriter();
    }
}
