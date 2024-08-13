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

    $name = __DIR__ . '/../test';
    $builder = DataExporter::csv([['a']]);
    $realName = $builder->makeFilename($name);
    expect($realName)->toBe(str_replace('\\', '/', dirname(__DIR__)) . '/test.csv');

    // windows
    $name = 'G:\\\\abc\\test';
    $builder = DataExporter::csv([['a']]);
    $realName = $builder->makeFilename($name);
    expect($realName)->toBe('G://abc/test.csv');

    // linux
    $name = '/abc/test';
    $builder = DataExporter::csv([['a']]);
    $realName = $builder->makeFilename($name);
    expect($realName)->toBe('/abc/test.csv');
});

it('Handler makeFile can solve invalid character', function () {
    $name = 'abc /\\:*"<>|.csv?x=1';
    $builder = DataExporter::csv([['a']]);

    $realName = $builder->makeFilename($name);
    expect($realName)->toBe('abc /_____.csv'); // / 被认为是路径分隔符，因此留下了

    $realName = $builder->makeFilename($name, false);
    expect($realName)->toBe('abc _______.csv'); // 设置不含 path 后，/ 不会被认为是路径分隔符

    $name = 'abc /\\:*"<>|?x=1';
    $builder = DataExporter::csv([['a']]);

    $realName = $builder->makeFilename($name, false);
    expect($realName)->toBe('abc ________x=1.csv'); // 因为不存在 .csv 后缀在 name 中，因此 ? 会被保留替换
});
