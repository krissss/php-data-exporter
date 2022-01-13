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
- GeneratorChainSourceIterator, Example in [Tests](./tests/Feature/GeneratorChainSourceIteratorTest.php)
- see [Tests](./tests/Feature/SourceTest.php)

### Support Writer

All Config in `DataExporter::writerConfig()`, see [Tests](./tests/Feature/WriterTest.php)

You can extend DataExporter and add Yours, see Example in [Tests](./tests/Feature/ExtraWriter.php)

## FAQ

> Why [box/spout](https://github.com/box/spout) Use

`box/spout` can write xlsx use stream, but `phpoffice/phpspreadsheet` not.
`phpoffice/phpspreadsheet` use lots of memory when write huge data, but `box/spout` use few!

> When use `GeneratorChainSourceIterator`

When you should handle huge source and need to merge them in one write.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [kriss](https://github.com/kriss)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
