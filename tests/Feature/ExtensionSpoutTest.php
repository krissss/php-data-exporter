<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\WriterInterface;

/**
 * 配置 csv
 */
class ConfigCsvExtend extends NullSpoutExtend
{
    /**
     * @inheritDoc
     * @link https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#configuration-for-csv
     */
    public function beforeOpen(WriterInterface $writer): void
    {
        if ($writer instanceof \OpenSpout\Writer\CSV\Writer) {
            $options = $writer->getOptions();
            $options->FIELD_DELIMITER = '|';
        }
    }
}

/**
 * 默认样式
 */
class DefaultStyleExtend extends NullSpoutExtend
{
    /**
     * @inheritDoc
     */
    public function beforeOpen(WriterInterface $writer): void
    {

        if ($writer instanceof OpenSpout\Writer\XLSX\Writer || $writer instanceof OpenSpout\Writer\ODS\Writer) {
            $style = (new Style())
                ->setFontName('Arial')
                ->setFontSize(11)
                ->setFontColor(Color::GREEN);
            $options = $writer->getOptions();
            $options->DEFAULT_ROW_STYLE = $style;
        }
    }
}

/**
 * 行和列的样式
 */
class RowCellStyleExtend extends NullSpoutExtend
{
    /**
     * @inheritDoc
     */
    public function buildCellStyle(int|string $colIndex, int $rowIndex): ?Style
    {
        if ($colIndex === 0 && $rowIndex === 2) {
            // 第一列，第二行（A2）
            return (new Style())
                ->setFontColor(Color::RED)
                ->setFontBold();
        }
        if ($colIndex === 1 && $rowIndex === 2) {
            // 第二列，第二行（B2）
            return (new Style())
                ->setFontColor(Color::WHITE)
                ->setBackgroundColor(Color::BLACK);
        }
        if ($colIndex === 0 && $rowIndex === 2) {
            // 第一列，第五行（A5）
            return (new Style())
                ->setShouldWrapText();
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function buildRowStyle(int $rowIndex): ?Style
    {
        if ($rowIndex === 3) {
            // 第三行
            return (new Style())
                ->setBackgroundColor(Color::BLUE);
        }
        if ($rowIndex === 1) {
            // 第一行
            return (new Style())
                ->setCellAlignment(\OpenSpout\Common\Entity\Style\CellAlignment::CENTER);
        }
        if ($rowIndex === 6) {
            // 第五行
            return (new Style())
                ->setShouldShrinkToFit();
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function beforeClose(WriterInterface $writer): void
    {
        if ($writer instanceof \OpenSpout\Writer\XLSX\Writer) {
            $options = $writer->getOptions();
            // 合并单元格
            // https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#cell-merging
            $options->mergeCells(0, 5, 2, 5);
            // 设置列宽
            // https://github.com/openspout/openspout/blob/4.x/docs/documentation.md#column-widths
            $options->setColumnWidth(60, 1);
        }
    }
}

beforeEach(function () {
    $this->source = [
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['This is long For fonts and alignments, OpenSpout does not support all the possible formatting options yet. But you can find the most important ones', '', ''],
        ['This is long For fonts and alignments, OpenSpout does not support all the possible formatting options yet. But you can find the most important ones', '', ''],
    ];
    $this->filename = __DIR__ . '/../tmp/test';
});

it("Extension Spout: config csv", function () {
    $filename = DataExporter::csvSpout($this->source, [
        'showHeaders' => false,
        'extend' => new ConfigCsvExtend(),
    ])->saveAs($this->filename);

    $options = new \OpenSpout\Reader\CSV\Options();
    $options->FIELD_DELIMITER = '|';
    $reader = new \OpenSpout\Reader\CSV\Reader($options);
    $reader->open($filename);
    $firstRow = [];
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            /** @var Row $row */
            $firstRow = $row->toArray();

            break;
        }
    }

    expect($this->source[0])->toEqual($firstRow);
});

it("Extension Spout: set default style", function () {
    DataExporter::xlsxSpout($this->source, [
        'extend' => new DefaultStyleExtend(),
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});

it("Extension Spout: set cell or row style", function () {
    DataExporter::xlsxSpout($this->source, [
        'showHeaders' => false,
        'extend' => new RowCellStyleExtend(),
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});
