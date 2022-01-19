<?php

namespace Kriss\DataExporter\Source;

use Iterator;
use Sonata\Exporter\Source\SourceIteratorInterface;

class CallableSourceIterator implements SourceIteratorInterface
{
    protected $callable;

    public function __construct(callable $callback)
    {
        $this->callable = $callback;
    }

    protected $callableResult;

    protected function parseCallable(): Iterator
    {
        if ($this->callableResult === null) {
            $this->callableResult = call_user_func($this->callable);
        }

        return $this->callableResult;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->parseCallable()->current();
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->parseCallable()->next();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->parseCallable()->key();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->parseCallable()->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->parseCallable()->rewind();
    }
}
