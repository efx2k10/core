<?php

namespace APP\Providers;

use APP\Listeners\ContentLengthEventListener;
use APP\Listeners\HandleModelEventListener;
use APP\Listeners\ServerErrorEventListener;
use Efx\Core\Dbal\Event\ModelPersist;
use Efx\Core\Event\EventDispatcher;
use Efx\Core\Http\Events\ResponseEvent;
use Efx\Core\Providers\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{

    private array $listeners = [
        ResponseEvent::class => [
            ServerErrorEventListener::class,
            ContentLengthEventListener::class
        ],
        ModelPersist::class => [
            HandleModelEventListener::class
        ]
    ];

    public function __construct(
        private EventDispatcher  $eventDispatcher,
    )
    {
    }

    public function register()
    {
        foreach ($this->listeners as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->eventDispatcher->addListener($event, new $listener);
            }
        }
    }
}