<?php

namespace Efx\Core\Http\Middleware;

use Efx\Core\Http\Request;
use Efx\Core\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}