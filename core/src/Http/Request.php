<?php

namespace Efx\Core\Http;

use Efx\Core\Session\SessionInterface;

class Request
{
    private SessionInterface $session;

    private mixed $routeHandler;
    private array $routeArgs;


    public function __construct(
        private readonly array $getParams,
        private readonly array $postData,
        private readonly array $cookies,
        private readonly array $files,
        private readonly array $server,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static ($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function path(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function method(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function input(string $key, $default = null)
    {
        return $this->postData[$key] ?? $default;
    }

    public function param(string $key, $default = null)
    {
        return $this->getParams[$key] ?? $default;
    }

    public function getRouteHandler(): mixed
    {
        return $this->routeHandler;
    }

    public function setRouteHandler(mixed $routeHandler): void
    {
        $this->routeHandler = $routeHandler;
    }

    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }

    public function setRouteArgs(array $routeArgs): void
    {
        $this->routeArgs = $routeArgs;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getFiles(): array
    {
        return $this->files;
    }


}