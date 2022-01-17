<?php

namespace Kriss\DataExporter\Writer;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\WriterInterface;
use Box\Spout\Writer\XLSX\Writer;
use Kriss\DataExporter\Writer\Interfaces\ExcelSheetSupportInterface;
use Kriss\DataExporter\Writer\Traits\ExcelSheetSpoutTrait;
use Kriss\DataExporter\Writer\Traits\XlsxTypedTrait;

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
