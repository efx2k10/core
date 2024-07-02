<?php

namespace Efx\Core\Http\Exceptions;

class NotFoundException extends HttpException
{
    public function __construct(
        string     $message = "Not Found",
        int        $code = 404
    )
    {
        parent::__construct($message, $code, null);
        $this->setMessage($message);
        $this->setStatusCode($code);
    }
}