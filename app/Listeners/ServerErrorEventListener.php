<?php

namespace APP\Listeners;

use Efx\Core\Http\Events\ResponseEvent;

class ServerErrorEventListener
{
    public function __invoke(ResponseEvent $event): void
    {
        if ($event->getResponse()->getStatusCode() >= 500)
            $event->stopPropagation();
    }
}