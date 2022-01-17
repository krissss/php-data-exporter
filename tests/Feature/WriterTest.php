<?php

use Kriss\DataExporter\DataExporter;
use PhpOffice\PhpSpreadsheet\IOFactory;

beforeEach(function () {
    $this->source = [
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
    ];
    $this->filename = __DIR__ . '/../tmp/test';
});

it('Writer csv', function () {
    $filename = DataExporter::csv($this->source)->saveAs($this->filename);

    expect($this->filename . '.csv')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsx', function () {
    $filename = DataExporter::xlsx($this->source)->saveAs($this->filename);

    expect($this->filename . '.xlsx')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xls', function () {
    $filename = DataExporter::xls($this->source)->saveAs($this->filename);

    expect($this->filename . '.xls')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer csvSpout', function () {
    $filename = DataExporter::csvSpout($this->source)->saveAs($this->filename);

    expect($this->filename . '.csv')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsxSpout', function () {
    $filename = DataExporter::xlsxSpout($this->source)->saveAs($this->filename);

    expect($this->filename . '.xlsx')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer odsSpout', function () {
    $filename = DataExporter::odsSpout($this->source)->saveAs($this->filename);

    expect($this->filename . '.ods')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer csvSpreadsheet', function () {
    $filename = DataExporter::csvSpreadsheet($this->source)->saveAs($this->filename);

    expect($this->filename . '.csv')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsSpreadsheet', function () {
    $filename = DataExporter::xlsSpreadsheet($this->source)->saveAs($this->filename);

    expect($this->filename . '.xls')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsxSpreadsheet', function () {
    $filename = DataExporter::xlsxSpreadsheet($this->source)->saveAs($this->filename);

    expect($this->filename . '.xlsx')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer odsSpreadsheet', function () {
    $filename = DataExporter::odsSpreadsheet($this->source)->saveAs($this->filename);

    expect($this->filename . '.ods')->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});
