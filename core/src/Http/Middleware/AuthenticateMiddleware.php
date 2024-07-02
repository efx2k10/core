<?php

namespace Efx\Core\Http\Middleware;

use Efx\Core\Auth\SessionAuthInterface;
use Efx\Core\Http\Redirect;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use Efx\Core\Session\SessionInterface;

class AuthenticateMiddleware implements MiddlewareInterface
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

        if (!$this->auth->check()) {

            $this->session->setFlash('error', 'need auth');
            return new Redirect('/login');
        };

        return $handler->handle($request);
    }
}