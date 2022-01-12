<?php

use Kriss\DataExporter\DataExporter;

it('Clean file if exception', function () {
    $source = [
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
    ];
    $builder = DataExporter::csv($source)
        ->withEvents([
            DataExporter\Builder::EVENT_AFTER_ECHO_ROW_WRITE => function ($data, $index) {
                if ($index === 3) {
                    throw new Exception('Error');
                }
            },
        ]);
    $filename = $builder->makeFilename(__DIR__ . '/../tmp/test');
    try {
        $builder->saveAs(__DIR__ . '/../tmp/test');
    } catch (Throwable $e) {
        $builder->clean();
        expect(file_exists($filename))->toBe(false);
    }
});
