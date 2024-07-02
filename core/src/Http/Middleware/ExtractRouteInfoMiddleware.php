<?php

namespace Efx\Core\Http\Middleware;

use Efx\Core\Http\Exceptions\MethodNotAllowedException;
use Efx\Core\Http\Exceptions\RouteNotFoundException;
use Efx\Core\Http\Request;
use Efx\Core\Http\Response;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class ExtractRouteInfoMiddleware implements MiddlewareInterface
{
    public function __construct(
        private array $routes,
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {

        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = $this->routes;
            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->method(), $request->path());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                $request->setRouteHandler($routeInfo[1][0]);
                $request->setRouteArgs($routeInfo[2]);
                $handler->injectMiddleware($routeInfo[1][1]);
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                $exception = new MethodNotAllowedException("Supported HTTP Method: $allowedMethods");
                $exception->setStatusCode(405);
                throw $exception;
            default:
                $exception = new RouteNotFoundException("Route not found");
                $exception->setStatusCode(404);
                throw $exception;
        }


        return $handler->handle($request);
    }
}