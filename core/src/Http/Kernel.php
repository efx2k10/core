<?php

namespace Efx\Core\Http;


use Efx\Core\Event\EventDispatcher;
use Efx\Core\Http\Events\ResponseEvent;
use Efx\Core\Http\Exceptions\HttpException;
use Efx\Core\Http\Middleware\RequestHandlerInterface;
use League\Container\Container;

class Kernel
{
    private string $APP_ENV = "";

    public function __construct(
        private readonly Container      $container,
        private RequestHandlerInterface $requestHandler,
        private EventDispatcher         $eventDispatcher,
    )
    {
        $this->APP_ENV = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (HttpException $exception) {
            $response = new Response($exception->getMessage(), $exception->getStatusCode());
        } catch (\Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        $request->getSession()->clearFlash();
    }

    private function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array($this->APP_ENV, ["dev", "test"])) {
            throw $exception;
        }

        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getStatusCode());
        }

        return new Response("Sever error", 500);
    }


}