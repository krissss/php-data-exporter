<?php

namespace Kriss\DataExporter\Source;

class ExcelSheetSourceIterator extends GeneratorChainSourceIterator
{
    /**
     * @param array $sheetIterator
     */
    public function __construct(array $sheetIterator)
    {
        parent::__construct(function () use ($sheetIterator) {
            foreach ($sheetIterator as $sheetName => $iterator) {
                yield $sheetName => $iterator;
            }
        });
    }
}
