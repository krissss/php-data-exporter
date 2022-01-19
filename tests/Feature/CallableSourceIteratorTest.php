<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Source\CallableSourceIterator;
use Sonata\Exporter\Source\ArraySourceIterator;
use Sonata\Exporter\Source\ChainSourceIterator;

it("CallableSourceIterator", function () {
    $valueFromOuter = 'aaaa';
    $source = new CallableSourceIterator(function () use (&$valueFromOuter) {
        return new ArraySourceIterator([
            [$valueFromOuter],
        ]);
    });
    $valueFromOuter = 'bbbb';
    $filename = DataExporter::csv($source, [
        'showHeaders' => false,
    ])->saveAs(__DIR__ . '/../tmp/test');

    expect(strpos(file_get_contents($filename), $valueFromOuter) !== false)->toBeTrue();
});

it("CallableSourceIterator in Chain", function () {
    $total = 0;
    $source = new ChainSourceIterator([
        new CallableSourceIterator(function () use (&$total) {
            $arr = [];
            for ($i = 0; $i < 100; $i++) {
                $arr[] = ['x' . $i];
                $total++;
            }

            return new ArraySourceIterator($arr);
        }),
        new CallableSourceIterator(function () use (&$total) {
            return new ArraySourceIterator([
                ['total', $total],
            ]);
        }),
    ]);
    $filename = DataExporter::csv($source, [
        'showHeaders' => false,
    ])->saveAs(__DIR__ . '/../tmp/test');

    expect(strpos(file_get_contents($filename), '100') !== false)->toBeTrue();
});
