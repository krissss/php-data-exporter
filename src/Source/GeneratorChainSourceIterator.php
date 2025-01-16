<?php

namespace Kriss\DataExporter\Source;

use Sonata\Exporter\Source\SourceIteratorInterface;

class GeneratorChainSourceIterator implements SourceIteratorInterface
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
    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->generator->current();
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        $this->generator->next();
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->generator->key();
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->generator->valid();
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->generator->rewind();
    }
}
