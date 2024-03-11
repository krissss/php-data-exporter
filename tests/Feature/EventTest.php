<?php

use Kriss\DataExporter\DataExporter;

it("Event", function () {
    $source = [
        ['a1', 'a2', 'a3'],
        ['a1', 'a2', 'a3'],
        ['a1', 'a2', 'a3'],
        ['a1', 'a2', 'a3'],
        ['a1', 'a2', 'a3'],
    ];
    $count = 0;
    DataExporter::csv($source)
        ->withEvents([
            DataExporter\Builder::EVENT_AFTER_EACH_ROW_WRITE => function ($data, $index) use ($source, &$count) {
                expect($data)->toBe($source[$count])
                    ->and($index)->toBe($count);
                $count += 1;
            },
        ])
        ->saveAs(__DIR__ . '/../tmp/test');

    expect($count)->toBe(count($source));
});
