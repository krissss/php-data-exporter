<?php

namespace Kriss\DataExporter\Tests\Feature;

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
    if (!file_exists($filename)) {
        file_put_contents($filename, 'test');
    }
    try {
        MyDataExport::csv([['a']])->saveAs($filename);
    } catch (FileAlreadyExistException $e) {
        expect($e->filename)->toBe($filename);
    }
});
