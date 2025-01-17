<?php

namespace Efx\Core\Http;

class Redirect extends Response
{
    public function __construct(string $url)
    {
        parent::__construct("", 302, ['location' => $url]);
    }

    public function send(): void
    {
        header("Location: {$this->getHeader('location')}", true, $this->getStatusCode());
        exit;
    }
}