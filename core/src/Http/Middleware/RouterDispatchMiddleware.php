<?php

namespace Efx\Core\Http\Middleware;

use APP\Services\MyService;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use Efx\Core\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatchMiddleware implements MiddlewareInterface
{

    public function __construct(
        private readonly RouterInterface    $router,
        private readonly ContainerInterface $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

        $response = call_user_func_array($routeHandler, $vars);

        return $response;
    }
}