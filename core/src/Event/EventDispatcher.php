<?php

namespace Efx\Core\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    public function dispatch(object $event): object
    {
        foreach ($this->getListenersForEvent($event) as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped())
                return $event;

            $listener($event);
        }

        return $event;
    }

    public function getListenersForEvent(object $event): iterable
    {
        $eventName = get_class($event);

        if (array_key_exists($eventName, $this->listeners)) {
            return $this->listeners[$eventName];
        }

        return [];
    }

    public function addListener(string $event, callable $listener): static
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

}