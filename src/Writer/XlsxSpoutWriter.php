<?php

namespace Kriss\DataExporter\Writer;

use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Writer\XLSX\Writer;

class XlsxSpoutWriter extends BaseSpoutWriter implements ExcelSheetSupportInterface
{
    use XlsxTypedTrait;
    use ExcelSheetSpoutTrait;

    /**
     * @var Writer
     */
    protected $writer;

    /**
     * @inheritDoc
     */
    protected function getWriter(): WriterInterface
    {
        return WriterEntityFactory::createXLSXWriter();
    }
}
