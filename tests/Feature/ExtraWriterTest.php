<?php

namespace Kriss\DataExporter\Tests\Feature;

use Kriss\DataExporter\DataExporter\Handler;
use Sonata\Exporter\Writer\JsonWriter;

/**
 * @method static Handler json($source, array $options = [])
 */
class ExtraWriterDataExport extends \Kriss\DataExporter\DataExporter
{
    /**
     * @inheritDoc
     */
    public static function writerConfig(): array
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

it('export json', function () {
    $source = [
        ['aaa', 'bbb'],
        ['xxx', 'yyy'],
    ];
    $filename = ExtraWriterDataExport::json($source)->saveAs(__DIR__ . '/../tmp/ExtraWriter_json');

    expect(file_get_contents($filename))->toBe(json_encode($source));
});
