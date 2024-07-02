<?php

namespace Efx\Core\Http\Middleware;

use Efx\Core\Auth\SessionAuthInterface;
use Efx\Core\Http\Redirect;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use Efx\Core\Session\SessionInterface;

class GuestMiddleware implements MiddlewareInterface
{

    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface     $session
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if ($this->auth->check())
            return new Redirect('/dashboard');

        return $handler->handle($request);
    }
}