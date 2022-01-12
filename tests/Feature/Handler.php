<?php

use Illuminate\Support\Str;
use Kriss\DataExporter\DataExporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

it('Handler with saveAs', function () {
    $name = __DIR__ . '/../tmp/test';
    $filename = DataExporter::csv([['a']])->saveAs($name);

    expect($filename)->toBe($name . '.csv');
});

it('Handler with browserDownload', function () {
    $name = 'test';
    $response = DataExporter::csv([['a']])->browserDownload($name);

    expect($response)->toBeInstanceOf(StreamedResponse::class);
    expect(
        Str::contains($response->headers->get('Content-Disposition'), 'test.csv')
    )->toBeTrue();
});
