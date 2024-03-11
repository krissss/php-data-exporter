<?php

namespace Kriss\DataExporter\Source;

class GeneratorChainSourceIterator implements \Iterator
{
    private \Generator $generator;

    public function __construct(\Closure $generator)
    {
        $this->generator = call_user_func($generator);
    }

    public function current(): mixed
    {
        return $this->generator->current();
    }

    public function next(): void
    {
        $this->generator->next();
    }

    public function key(): mixed
    {
        return $this->generator->key();
    }

    public function valid(): bool
    {
        return $this->generator->valid();
    }

    public function rewind(): void
    {
        $this->generator->rewind();
    }
}
