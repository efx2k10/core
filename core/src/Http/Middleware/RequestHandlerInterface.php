<?php

namespace Efx\Core\Http\Middleware;

use Efx\Core\Http\Request;
use Efx\Core\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;

    public function injectMiddleware(array $middleware): void;

}