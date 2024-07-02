<?php

namespace Efx\Core\Routing;

use Efx\Core\Http\Request;
use League\Container\Container;

interface RouterInterface
{
    public function dispatch(Request $request, Container $container);
}