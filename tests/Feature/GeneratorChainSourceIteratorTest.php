<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Source\GeneratorChainSourceIterator;
use Sonata\Exporter\Source\ArraySourceIterator;

it("GeneratorChainSourceIterator", function () {
    $source = new GeneratorChainSourceIterator(function () {
        for ($i = 0; $i < 100; $i++) {
            yield new ArraySourceIterator([
                ['a' . $i . '-1'],
                ['a' . $i . '-2'],
                ['a' . $i . '-3'],
            ]);
        }
    });
    $filename = DataExporter::csv($source)->saveAs(__DIR__ . '/../tmp/test');

    expect(count(file($filename)))->toBe(301);
});

it("GeneratorChainSourceIterator nesting", function () {
    $source = new GeneratorChainSourceIterator(function () {
        yield new GeneratorChainSourceIterator(function () {
            yield new ArraySourceIterator([['a1']]);
            yield new ArraySourceIterator([['a2']]);
        });
        yield new GeneratorChainSourceIterator(function () {
            yield new ArraySourceIterator([['b1']]);
            yield new GeneratorChainSourceIterator(function () {
                yield new ArraySourceIterator([['c1']]);
            });
        });
    });
    $filename = DataExporter::csv($source)->saveAs(__DIR__ . '/../tmp/test');

    expect(count(file($filename)))->toBe(5);
});
