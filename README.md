# Data Export For PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kriss/data-export?style=flat-square)](https://packagist.org/packages/kriss/data-export)
[![Tests](https://github.com/krissss/php-data-exporter/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/krissss/php-data-exporter/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/kriss/data-export?style=flat-square)](https://packagist.org/packages/kriss/data-export)

Wrap [sonata-project/exporter](https://github.com/sonata-project/exporter), make it easy and strong~

## Feature

- Simple api (use `DataExporter::csv()->saveAs()`)
- Quick change for different output type (csv/xlsx/xls/ods...)
- Support a lot of source, and can use array or iterator quickly 
- Low memory usage with huge data write (use csv/xlsxSpout)
- Low memory usage with chunk data fetch and write by chain (use GeneratorChainSourceIterator)
- Support `saveAs()` and `browserDownload()`
- Support do something in writing (use ObjectEvent)
- Support control Spreadsheet and Spout Instance, for change style and others (use Extend)
- Support Excel multi sheet write (use ExcelSheetSourceIterator) 
- Support Dynamic source (use CallableSourceIterator)

## Installation

You can install the package via composer:

```bash
composer require kriss/data-export
```

## Usage

### simple Example

```php
use \Kriss\DataExporter\DataExporter;

$source = [
    ['aaa', 'bbb', 'ccc'],
    ['aaa', 'bbb', 'ccc'],
    ['aaa', 'bbb', 'ccc'],
];
DataExporter::csv($source, ['showHeaders' => false])->saveAs();
```

### Support Source

- all source defined in [sonata-project/exporter](https://docs.sonata-project.org/projects/exporter/en/2.x/reference/sources/)
- Simple source, Example in [Tests](./tests/Feature/SourceTest.php)
- GeneratorChainSourceIterator, Example in [Tests](./tests/Feature/GeneratorChainSourceIteratorTest.php)
- ExcelSheetSourceIterator, Example in [Tests](./tests/Feature/ExcelSheetSourceIteratorTest.php)
- CallableSourceIterator, Example in [Tests](./tests/Feature/CallableSourceIteratorTest.php)

### Support Writer

All Config in `DataExporter::writerConfig()`, see [Tests](./tests/Feature/WriterTest.php)

You can extend DataExporter and add Yours, see Example in [Tests](./tests/Feature/ExtraWriter.php)

## FAQ

> Why [box/spout](https://github.com/box/spout) Use

`box/spout` can write xlsx use stream, but `phpoffice/phpspreadsheet` not.
`phpoffice/phpspreadsheet` use lots of memory when write huge data, but `box/spout` use few!

> When use `GeneratorChainSourceIterator`

When you should handle huge source and need to merge them in one write.

> How to build style

Use extension, see [ExtendSpoutTest](./tests/Feature/ExtensionSpoutTest.php) or [ExtendSpreadsheetTest](./tests/Feature/ExtensionSpreadsheetTest.php)

> How to write multi sheet

Use ExcelSheetSpreadsheetTrait, see [ExcelSheetSourceIteratorTest](./tests/Feature/ExcelSheetSourceIteratorTest.php)
