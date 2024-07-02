<?php

namespace Efx\Core\Routing;

use Efx\Core\Controller\AbstractController;
use Efx\Core\Http\Exceptions\MethodNotAllowedException;
use Efx\Core\Http\Exceptions\RouteNotFoundException;
use Efx\Core\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use League\Container\Container;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes;

    public function dispatch(Request $request, Container $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class))
                $controller->setRequest($request);


            $handler = [$controller, $method];
        }

        return [$handler, $vars];

    }


}