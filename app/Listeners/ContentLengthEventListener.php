<?php

namespace APP\Listeners;

use Efx\Core\Http\Events\ResponseEvent;

class ContentLengthEventListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if (!array_key_exists('content-length', $response->getHeaders())) {
            $response->setHeader('content-length', strlen($response->getContent()));
        }
    }

}