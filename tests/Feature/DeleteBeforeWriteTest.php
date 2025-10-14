<?php

namespace Kriss\DataExporter\Tests\Feature;

use Illuminate\Container\Container;
use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Exceptions\FileAlreadyExistException;

class DontDeleteDataExport extends \Kriss\DataExporter\DataExporter
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
        DontDeleteDataExport::csv([['a']])->saveAs($filename);

        throw new \InvalidArgumentException();
    } catch (FileAlreadyExistException $e) {
        expect(true)->toBeTrue();
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
        DataExporter::csv([['a']])->saveAs($filename);

        throw new \InvalidArgumentException();
    } catch (FileAlreadyExistException $e) {
        expect(true)->toBeTrue();
    }
});
