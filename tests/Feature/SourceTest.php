<?php

use Kriss\DataExporter\DataExporter;

beforeEach(function () {
    $this->filename = __DIR__ . '/../tmp/test';
});

it('Source array', function () {
    $source = [
        ['a', 'b', 'c'],
        ['a', 'b', 'c'],
    ];
    $filename = DataExporter::csv($source)->saveAs($this->filename);

    expect(count(file($filename)))->toBe(3);
});

it('Source collection', function () {
    $source = collect([
        ['a', 'b', 'c'],
        ['a', 'b', 'c'],
    ]);
    $filename = DataExporter::csv($source)->saveAs($this->filename);

    expect(count(file($filename)))->toBe(3);
});

it('Source generator', function () {
    $source = (function () {
        for ($i = 0; $i < 10; $i++) {
            yield ['a' . $i];
        }
    })();
    $filename = DataExporter::csv($source)->saveAs($this->filename);

    expect(count(file($filename)))->toBe(11);
});

it('Source csv', function () {
    $csvFilename = $this->filename . '-input.csv';
    $fp = fopen($this->filename . '-input.csv', 'w');
    fputcsv($fp, ['header1', 'header2']);
    fputcsv($fp, ['aa', 'bb']);
    fputcsv($fp, ['aa', 'bb']);
    fputcsv($fp, ['aa', 'bb']);
    fclose($fp);

    $source = new \Sonata\Exporter\Source\CsvSourceIterator($csvFilename);
    $filename = DataExporter::csv($source)->saveAs($this->filename);

    expect(count(file($filename)))->toBe(4);
});
