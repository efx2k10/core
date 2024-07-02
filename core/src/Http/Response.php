<?php

namespace Efx\Core\Http;

class Response
{
    public function __construct(
        private string $content = '',
        private int    $statusCode = 200,
        private array  $headers = [],
    )
    {
        http_response_code($this->statusCode);
    }

    public function send(): void
    {
        ob_start();
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->content;

        ob_end_flush();
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function getHeader(string $key)
    {
        return $this->headers[$key] ?? null;
    }

    public function setHeader(string $key, mixed $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }


}