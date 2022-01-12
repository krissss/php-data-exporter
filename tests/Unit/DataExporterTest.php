<?php

use Kriss\DataExporter\DataExporter;

it('DataExporter __callStatic result type correct', function () {
    $exporter = DataExporter::xlsx([['a', 'b']]);
    expect($exporter)->toBeInstanceOf(DataExporter\Handler::class);
});

it('DataExporter saveAs result correct', function () {
    $savePath = __DIR__ . '/../tmp/test';
    $savePath = DataExporter::xlsx([['a', 'b']])->saveAs($savePath);

    expect($savePath)->toBe(__DIR__ . '/../tmp/test.xlsx');
});
