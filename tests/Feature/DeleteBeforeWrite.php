<?php

namespace Kriss\DataExporter\Tests\Feature;

use Illuminate\Container\Container;
use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Exceptions\FileAlreadyExistException;

class MyDataExport extends \Kriss\DataExporter\DataExporter
{
    /**
     * @inheritDoc
     */
    protected static function customConfig(): array
    {
        return [
            'deleteFirstIfExist' => false,
        ];
    }
}

it('Dont delete file if exist', function () {
    $filename = __DIR__ . '/../tmp/test.csv';
    if (! file_exists($filename)) {
        file_put_contents($filename, 'test');
    }

    try {
        MyDataExport::csv([['a']])->saveAs($filename);
    } catch (FileAlreadyExistException $e) {
        expect($e->filename)->toBe($filename);
    }
});

it('Dont delete file if exist use setContainer', function () {
    $filename = __DIR__ . '/../tmp/test.csv';
    if (! file_exists($filename)) {
        file_put_contents($filename, 'test');
    }

    $container = new Container();
    $container->singleton(DataExporter\Handler::CONTAINER_DATA_EXPORT_CONFIG_KEY, function () {
        return [
            'writer' => DataExporter::writerConfig(),
            'deleteFirstIfExist' => false,
        ];
    });
    DataExporter::setContainer($container);

    try {
        MyDataExport::csv([['a']])->saveAs($filename);
    } catch (FileAlreadyExistException $e) {
        expect($e->filename)->toBe($filename);
    }
});
