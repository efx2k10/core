<?php

namespace Efx\Core\Http\Events;

use Efx\Core\Event\Event;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private Request  $request,
        private Response $response
    )
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}