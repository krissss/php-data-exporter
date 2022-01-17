<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Source\ExcelSheetSourceIterator;
use PhpOffice\PhpSpreadsheet\IOFactory;

beforeEach(function () {
    $this->filename = __DIR__ . '/../tmp/test';
});

it('Excel sheet', function () {
    foreach (['xlsSpreadsheet', 'xlsxSpreadsheet', 'odsSpreadsheet'] as $fn) {
        $source1 = new ExcelSheetSourceIterator([
            new ArrayIterator([
                ['aa', 'bb'],
                ['aa', 'bb'],
                ['aa', 'bb'],
            ]),
            new ArrayIterator([
                ['cc', 'dd'],
                ['cc', 'dd'],
                ['cc', 'dd'],
            ]),
        ]);
        $source2 = new ExcelSheetSourceIterator([
            'CustomerSheetName1' => new ArrayIterator([
                ['aa', 'bb'],
                ['aa', 'bb'],
                ['aa', 'bb'],
            ]),
            '中文名' => new ArrayIterator([
                ['cc', 'dd'],
                ['cc', 'dd'],
                ['cc', 'dd'],
            ]),
        ]);

        $filename = DataExporter::$fn($source1)->saveAs($this->filename);
        $factory = IOFactory::load($filename);
        expect(count($factory->getAllSheets()))->toBe(2);
        expect($factory->getActiveSheetIndex())->toBe(0);
        expect($factory->getActiveSheet()->getCell('B3')->getValue())->toBe('bb');

        $filename = DataExporter::$fn($source2)->saveAs($this->filename);
        $factory = IOFactory::load($filename);
        expect(count($factory->getAllSheets()))->toBe(2);
        $factory->setActiveSheetIndexByName('中文名');
        expect($factory->getActiveSheet()->getCell('B3')->getValue())->toBe('dd');
    }
});
