<?php

namespace Kriss\DataExporter\Tests\Feature;

use Kriss\DataExporter\DataExporter\Handler;
use Sonata\Exporter\Writer\JsonWriter;

/**
 * @method static Handler json($source, array $options = [])
 */
class MyDataExport extends \Kriss\DataExporter\DataExporter
{
    /**
     * @inheritDoc
     */
    protected static function writerConfig(): array
    {
        return array_merge(parent::writerConfig(), [
            'json' => [
                'class' => JsonWriter::class,
                'options' => [],
                'extension' => 'json',
            ],
        ]);
    }
}

it('Dont delete file if exist', function () {
    $source = [
        ['aaa', 'bbb'],
        ['xxx', 'yyy'],
    ];
    $filename = MyDataExport::json($source)->saveAs(__DIR__ . '/../tmp/test');

    expect(file_get_contents($filename))->toBe(json_encode($source));
});
