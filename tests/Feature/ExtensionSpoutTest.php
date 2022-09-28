<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\WriterInterface;

/**
 * 配置 csv
 */
class ConfigCsvExtend extends NullSpoutExtend
{
    /**
     * @inheritDoc
     * @link https://github.com/openspout/openspout/blob/3.x/docs/documentation.md#configuration-for-csv
     */
    public function beforeOpen(WriterInterface $writer)
    {
        if ($writer instanceof \OpenSpout\Writer\CSV\Writer) {
            $writer->setFieldDelimiter('|');
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
    public function beforeOpen(WriterInterface $writer)
    {
        $style = (new StyleBuilder())
            ->setFontName('Arial')
            ->setFontSize(11)
            ->setFontColor(Color::GREEN)
            ->build();
        $writer->setDefaultRowStyle($style);
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
    public function afterCellCreate($colIndex, int $rowIndex, Cell $cell): void
    {
        if ($colIndex === 0 && $rowIndex === 2) {
            // 第一列，第二行（A2）
            $cell->setStyle(
                (new StyleBuilder())
                    ->setFontColor(Color::RED)
                    ->setFontBold()
                    ->build()
            );

            return;
        }
        if ($colIndex === 1 && $rowIndex === 2) {
            // 第二列，第二行（B2）
            $cell->setStyle(
                (new StyleBuilder())
                    ->setFontColor(Color::WHITE)
                    ->setBackgroundColor(Color::BLACK)
                    ->build()
            );

            return;
        }
        if ($colIndex === 0 && $rowIndex === 2) {
            // 第一列，第五行（A5）
            $cell->setStyle(
                (new StyleBuilder())
                    ->setShouldWrapText()
                    ->build()
            );

            return;
        }

        return;
    }

    /**
     * @inheritDoc
     */
    public function afterRowCreate(int $rowIndex, Row $row): void
    {
        if ($rowIndex === 3) {
            // 第三行
            $row->setStyle(
                (new StyleBuilder())
                    ->setBackgroundColor(Color::BLUE)
                    ->build()
            );

            return;
        }
        if ($rowIndex === 1) {
            // 第一行
            $row->setStyle(
                (new StyleBuilder())
                    ->setCellAlignment(\OpenSpout\Common\Entity\Style\CellAlignment::CENTER)
                    ->build()
            );

            return;
        }
        if ($rowIndex === 6) {
            // 第五行
            $row->setStyle(
                (new StyleBuilder())
                ->setShouldShrinkToFit()
                ->build()
            );

            return;
        }

        return;
    }

    /**
     * @inheritDoc
     */
    public function beforeClose(WriterInterface $writer): void
    {
        if ($writer instanceof \OpenSpout\Writer\XLSX\Writer) {
            // 合并单元格
            $writer->mergeCells([0, 5], [2, 5])
                ->setColumnWidth(60, 1);
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

    /** @var \OpenSpout\Reader\CSV\Reader $reader */
    $reader = \OpenSpout\Reader\Common\Creator\ReaderEntityFactory::createCSVReader();
    $reader->setFieldDelimiter('|');
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
