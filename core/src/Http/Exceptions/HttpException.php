<?php

namespace Efx\Core\Http\Exceptions;

class HttpException extends \Exception
{
private int $statusCode  = 400;
protected $message;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }


    public function setMessage($message): void
    {
        $this->message = $message;
    }
}