<?php

use Box\Spout\Common\Entity\Cell;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Common\Entity\Style\Style;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\WriterInterface;
use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Writer\Extension\NullSpoutExtend;

class ExtensionSpoutDefaultStyleExtend extends NullSpoutExtend
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

class ExtensionSpoutRowCellStyleExtend extends NullSpoutExtend
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
        'extend' => new ExtensionSpoutDefaultStyleExtend(),
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});

it("Extension Spout: set cell or row style", function () {
    DataExporter::xlsxSpout($this->source, [
        'showHeaders' => false,
        'extend' => new ExtensionSpoutRowCellStyleExtend(),
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});

it("Extension Spout: col index", function () {
    DataExporter::xlsxSpout([
        ['aa', 'bb', 'cc'],
    ], [
        'showHeaders' => false,
        'extend' => new class () extends NullSpoutExtend {
            public function buildCellStyle($colIndex, $rowIndex): ?Style
            {
                expect($colIndex)->toBeInt()
                    ->and($rowIndex)->toBeInt();

                return null;
            }
        },
    ])->saveAs($this->filename);

    DataExporter::xlsxSpout([
        ['key1' => 'aa', 'key2' => 'bb'],
    ], [
        'showHeaders' => false,
        'extend' => new class () extends NullSpoutExtend {
            public function buildCellStyle($colIndex, $rowIndex): ?Style
            {
                expect($colIndex)->toBeString()
                    ->and($rowIndex)->toBeInt();

                return null;
            }
        },
    ])->saveAs($this->filename);
});

it("Extension Spout: change style use context", function () {
    DataExporter::xlsxSpout([
        [10, 2, 20, 4],
        [20, 6, 10, 8],
    ], [
        'showHeaders' => false,
        'extend' => new class () extends NullSpoutExtend {
            public function buildCellStyleWithContext($colIndex, int $rowIndex, array $context): ?Style
            {
                if ($colIndex === 0) {
                    $rowData = $context['row_data'];
                    if ($rowData[2] > $rowData[0]) {
                        return (new StyleBuilder())
                            ->setFontColor(Color::GREEN)
                            ->build();
                    }
                }

                return null;
            }
        },
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});

it("Extension Spout: change cell", function () {
    DataExporter::xlsxSpout([
        [10, 2, 20, 4],
        [20, 6, 10, 8],
    ], [
        'showHeaders' => false,
        'extend' => new class () extends NullSpoutExtend {
            public function buildCell($colIndex, int $rowIndex, Cell $cell, array $context = []): Cell
            {
                if ($colIndex == 0 && $rowIndex == 1) {
                    $cell->setValue('new value');
                }

                return $cell;
            }
        },
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});
