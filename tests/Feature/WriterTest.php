<?php

use Carbon\Carbon;
use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Writer\Expression\HyperLinkExpression;
use Kriss\DataExporter\Writer\Expression\TypedExpression;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Filesystem\Path;

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

    expect(Path::canonicalize($this->filename . '.csv'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsx', function () {
    $filename = DataExporter::xlsx($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.xlsx'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xls', function () {
    $filename = DataExporter::xls($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.xls'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer csvSpout', function () {
    $filename = DataExporter::csvSpout($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.csv'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsxSpout', function () {
    $filename = DataExporter::xlsxSpout($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.xlsx'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer odsSpout', function () {
    $filename = DataExporter::odsSpout($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.ods'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer csvSpreadsheet', function () {
    $filename = DataExporter::csvSpreadsheet($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.csv'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsSpreadsheet', function () {
    $filename = DataExporter::xlsSpreadsheet($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.xls'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer xlsxSpreadsheet', function () {
    $filename = DataExporter::xlsxSpreadsheet($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.xlsx'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('Writer odsSpreadsheet', function () {
    $filename = DataExporter::odsSpreadsheet($this->source)->saveAs($this->filename);

    expect(Path::canonicalize($this->filename . '.ods'))->toBe($filename);
    $factory = IOFactory::load($filename);
    expect((string)$factory->getActiveSheet()->getCell('C4')->getValue())->toBe('cc');
});

it('write xlsx with dataType', function () {
    $source = [
        [
            'string' => 'abc',
            'string_int' => '123',
            'string_int_long' => '123456789101112',
            'string_float' => '12.5',
            'string_float_long' => '12.123456789101112',
            'string_float_00' => '12.00',
            'int' => 123,
            'int_long' => 123456789101112,
            'float' => 12.5,
            'float_long' => 12.123456789101112,
            'float_00' => 12.00,
            'bool_false' => false,
            'bool_true' => true,
            'null' => null,
            'date' => '2021-01-01',
            'datetime' => '2021-01-01 12:00:00',
            'time' => '12:00:00',
            'date_carbon' => Carbon::now(),
            'hyperlink' => new HyperLinkExpression('https://www.baidu.com', '百度'),
            'hyperlink2' => new HyperLinkExpression('https://www.baidu.com?name=a"b', '百"度'),
            'formula' => '=SUM(G2:H2)',
            'formula_string' => new TypedExpression('=SUM(G2:H2)', TypedExpression::TYPE_STRING),
        ],
    ];

    DataExporter::xlsx($source)->saveAs($this->filename . '-xlsx');
    DataExporter::xlsxSpreadsheet($source)->saveAs($this->filename . '-xlsx-spreadsheet');
    DataExporter::xlsxSpout($source)->saveAs($this->filename . '-xlsx-spout');

    // check by person
    expect(true)->toBeTrue();
});
