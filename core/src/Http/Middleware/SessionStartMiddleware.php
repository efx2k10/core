<?php

namespace Efx\Core\Http\Middleware;

use APP\Services\MyService;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use Efx\Core\Routing\RouterInterface;
use Efx\Core\Session\SessionInterface;
use Psr\Container\ContainerInterface;

class SessionStartMiddleware implements MiddlewareInterface
{

    public function __construct(
        private SessionInterface $session,
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }
}