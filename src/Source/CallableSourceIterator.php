<?php

namespace Kriss\DataExporter\Source;

use Iterator;

class CallableSourceIterator implements Iterator
{
    protected $callable;

    public function __construct(callable $callback)
    {
        $this->callable = $callback;
    }

    protected mixed $callableResult = null;

    protected function parseCallable(): Iterator
    {
        if ($this->callableResult === null) {
            $this->callableResult = call_user_func($this->callable);
        }

        return $this->callableResult;
    }

    public function current(): mixed
    {
        return $this->parseCallable()->current();
    }

    public function next(): void
    {
        $this->parseCallable()->next();
    }

    public function key(): mixed
    {
        return $this->parseCallable()->key();
    }

    public function valid(): bool
    {
        return $this->parseCallable()->valid();
    }

    public function rewind(): void
    {
        $this->parseCallable()->rewind();
    }
}
