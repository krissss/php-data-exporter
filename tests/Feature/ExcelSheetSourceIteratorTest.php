<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Source\ExcelSheetSourceIterator;
use Kriss\DataExporter\Source\GeneratorChainSourceIterator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Sonata\Exporter\Source\ArraySourceIterator;

beforeEach(function () {
    $this->filename = __DIR__ . '/../tmp/test';
});

it('Excel sheet: ExcelSheetSourceIterator', function () {
    foreach ([
                 'xlsSpreadsheet', 'xlsxSpreadsheet', 'odsSpreadsheet',
                 'xlsxSpout', 'odsSpout',
             ] as $fn) {
        $source1 = new ExcelSheetSourceIterator([
            new ArrayIterator([
                ['aa', 'bb1'],
                ['aa', 'bb2'],
                ['aa', 'bb3'],
            ]),
            new ArrayIterator([
                ['cc', 'dd4'],
                ['cc', 'dd5'],
                ['cc', 'dd6'],
            ]),
        ]);
        $source2 = new ExcelSheetSourceIterator([
            'CustomerSheetName1' => new ArrayIterator([
                ['aa', 'bb1'],
                ['aa', 'bb2'],
                ['aa', 'bb3'],
            ]),
            '中文名' => new ArrayIterator([
                ['cc', 'dd4'],
                ['cc', 'dd5'],
                ['cc', 'dd6'],
            ]),
        ]);
        $source3 = new ExcelSheetSourceIterator([
            'withHeader' => new ArrayIterator([
                ['key1' => 'cc', 'name1' => 'aa', 'bb1'],
                ['aa', 'bb2'],
                ['aa', 'bb3'],
            ]),
            '有表头' => new ArrayIterator([
                ['key2' => 'cc', 'name2' => 'dd4'],
                ['cc', 'dd5'],
                ['cc', 'dd6'],
            ]),
        ]);

        $filename = DataExporter::$fn($source1)->saveAs($this->filename);
        $factory = IOFactory::load($filename);
        expect(count($factory->getAllSheets()))->toBe(2);
        expect($factory->getActiveSheetIndex())->toBe(0);
        expect((string)$factory->getActiveSheet()->getCell('B3')->getValue())->toBe('bb3');

        $filename = DataExporter::$fn($source2)->saveAs($this->filename);
        $factory = IOFactory::load($filename);
        expect(count($factory->getAllSheets()))->toBe(2);
        $factory->setActiveSheetIndexByName('中文名');
        expect((string)$factory->getActiveSheet()->getCell('B3')->getValue())->toBe('dd6');

        $filename = DataExporter::$fn($source3)->saveAs($this->filename);
        $factory = IOFactory::load($filename);
        expect(count($factory->getAllSheets()))->toBe(2);
        $factory->setActiveSheetIndexByName('有表头');
        expect((string)$factory->getActiveSheet()->getCell('A1')->getValue())->toBe('key2');
    }
});

it('Excel sheet: GeneratorChainSourceIterator', function () {
    foreach ([
                 'xlsSpreadsheet', 'xlsxSpreadsheet', 'odsSpreadsheet',
                 'xlsxSpout', 'odsSpout',
             ] as $fn) {
        $source1 = new GeneratorChainSourceIterator(function () {
            foreach ([
                         new ArrayIterator([
                             ['aa', 'bb1'],
                             ['aa', 'bb2'],
                             ['aa', 'bb3'],
                         ]),
                         new ArrayIterator([
                             ['cc', 'dd4'],
                             ['cc', 'dd5'],
                             ['cc', 'dd6'],
                         ]),
                     ] as $iterator) {
                yield $iterator;
            }
        });
        $excelSource = new ExcelSheetSourceIterator([
            'MySheet1' => new ArraySourceIterator([
                ['a'],
            ]),
            'MySheet233' => $source1,
        ]);

        $filename = DataExporter::$fn($excelSource)->saveAs($this->filename);
        $factory = IOFactory::load($filename);
        expect(count($factory->getAllSheets()))->toBe(2);
        expect($factory->getActiveSheetIndex())->toBe(0);
        $factory->setActiveSheetIndexByName('MySheet233');
        expect($factory->getActiveSheet()->getHighestRow())->toBe(6);
        expect((string)$factory->getActiveSheet()->getCell('B3')->getValue())->toBe('bb3');
    }
});
