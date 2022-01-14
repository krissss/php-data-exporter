<?php

use Kriss\DataExporter\DataExporter;

beforeEach(function () {
    $this->simpleSource = [
        ['aa', 'bb'],
        ['aa', 'bb'],
        ['aa', 'bb'],
        ['aa', 'bb'],
    ];
    $this->headerSource = [
        ['name' => 'aaa', 'age' => 10],
        ['name' => 'aaa', 'age' => 10],
        ['name' => 'aaa', 'age' => 10],
        ['name' => 'aaa', 'age' => 10],
    ];
    $this->filename = __DIR__ . '/../tmp/test';
});

it("Header set: auto", function () {
    $filename = DataExporter::csvSpout($this->simpleSource)->saveAs($this->filename);
    expect(count(file($filename)))->toBe(4);

    $filename = DataExporter::csvSpout($this->headerSource)->saveAs($this->filename);
    expect(count(file($filename)))->toBe(5);
});

it("Header set: false", function () {
    $filename = DataExporter::csvSpout($this->simpleSource, ['showHeaders' => true])->saveAs($this->filename);
    expect(count(file($filename)))->toBe(5);
});

it("Header set: true", function () {
    $filename = DataExporter::csvSpout($this->headerSource, ['showHeaders' => false])->saveAs($this->filename);
    expect(count(file($filename)))->toBe(4);
});
