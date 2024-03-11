<?php

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
        str_contains($response->headers->get('Content-Disposition'), 'test.csv')
    )->toBeTrue();
});

it('Handler getFilename', function () {
    $name = 'test';
    $builder = DataExporter::csv([['a']]);
    $realName = $builder->makeFilename($name);

    expect($realName)->toBe('test.csv');
});
