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
            // 时间
            'date' => new TypedExpression('2021-01-01', TypedExpression::TYPE_DATE),
            'datetime' => new TypedExpression('2021-01-01 12:52:18', TypedExpression::TYPE_DATE),
            'time' => new TypedExpression('12:52:18', TypedExpression::TYPE_DATE),
            'date_carbon' => new TypedExpression(Carbon::now(), TypedExpression::TYPE_DATE),
            'date_datetime_obj' => new TypedExpression(new DateTime(), TypedExpression::TYPE_DATE),
            'date_string' => '2021-01-01', // 不自动识别
            'datetime_string' => '2021-01-01 12:52:18', // 不自动识别
            'time_string' => '12:12:52', // 不自动识别
            'carbon_string' => Carbon::now(), // 会处理成时间字符串
            'datetime_obj_string' => new DateTime(), // 会处理成时间字符串
            // 超链接
            'hyperlink' => new HyperLinkExpression('https://www.baidu.com', '百度'),
            'hyperlink2' => new HyperLinkExpression('https://www.baidu.com?name=a"b', '百"度'),
            'hyperlink_string' => 'https://www.baidu.com', // 不自动识别
            // 公式
            'formula' => new TypedExpression('=SUM(G2:H2)', TypedExpression::TYPE_FORMULA),
            'formula_string' => '=SUM(G2:H2)', // 不自动识别
            'formula_string2' => '=(WX000', // 不自动识别
        ],
    ];

    DataExporter::xlsx($source)->saveAs($this->filename . '-xlsx');
    DataExporter::xlsxSpreadsheet($source)->saveAs($this->filename . '-xlsx-spreadsheet');
    DataExporter::xlsxSpout($source)->saveAs($this->filename . '-xlsx-spout');

    // check by person
    expect(true)->toBeTrue();
});

it('change default datetime format', function () {
    $defaultDateTimeFormat = DataExporter::$defaultDateTimeFormat;

    // 修改默认的时间格式
    DataExporter::$defaultDateTimeFormat = 'Y/m/d H-i-s';

    $source = [
        [
            'carbon_string' => Carbon::now(), // 会处理成时间字符串
            'datetime_obj_string' => new DateTime(), // 会处理成时间字符串
        ],
    ];

    DataExporter::xlsx($source)->saveAs($this->filename . '-xlsx');
    DataExporter::xlsxSpreadsheet($source)->saveAs($this->filename . '-xlsx-spreadsheet');
    DataExporter::xlsxSpout($source)->saveAs($this->filename . '-xlsx-spout');

    // check by person
    expect(true)->toBeTrue();

    // 重置回来，防止影响其他测试用例
    DataExporter::$defaultDateTimeFormat = $defaultDateTimeFormat;
});
