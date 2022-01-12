<?php

namespace Kriss\DataExporter\Traits;

trait ObjectEventsSupportTrait
{
    private $objectEvents = [];

    public function on(string $eventName, callable $handler): self
    {
        $this->objectEvents[$eventName][] = $handler;

        return $this;
    }

    public function handleEvent($eventName, ...$args): void
    {
        if (! isset($this->objectEvents[$eventName])) {
            return;
        }
        foreach ($this->objectEvents[$eventName] as $handler) {
            call_user_func_array($handler, $args);
        }
    }
}
