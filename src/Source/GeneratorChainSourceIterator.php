<?php

namespace Kriss\DataExporter\Source;

class GeneratorChainSourceIterator implements \Iterator
{
    /**
     * @var \Generator
     */
    private $generator;

    public function __construct(\Closure $generator)
    {
        $this->generator = call_user_func($generator);
        if (! $this->generator instanceof \Generator) {
            throw new \InvalidArgumentException('$generator must return \Generator');
        }
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->generator->current();
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->generator->next();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->generator->key();
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return $this->generator->valid();
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->generator->rewind();
    }
}
