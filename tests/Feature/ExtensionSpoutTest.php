<?php

use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\WriterInterface;
use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;

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

class RowCellStyleExtend extends NullSpoutExtend
{
    /**
     * @inheritDoc
     */
    public function buildCellStyle($colIndex, $rowIndex): ?Style
    {
        if ($colIndex === 1 && $rowIndex === 2) {
            return (new StyleBuilder())
                ->setFontColor(Color::RED)
                ->setFontBold()
                ->build();
        }
        if ($colIndex === 2 && $rowIndex === 2) {
            return (new StyleBuilder())
                ->setFontColor(Color::WHITE)
                ->setBackgroundColor(Color::BLACK)
                ->build();
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function buildRowStyle($rowIndex): ?Style
    {
        if ($rowIndex === 3) {
            return (new StyleBuilder())
                ->setBackgroundColor(Color::BLUE)
                ->build();
        }

        return null;
    }
}

beforeEach(function () {
    $this->source = [
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
    ];
    $this->filename = __DIR__ . '/../tmp/test';
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
