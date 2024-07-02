<?php
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';


use Efx\Core\Http\Kernel;
use Efx\Core\Http\Request;


$request = Request::createFromGlobals();

/** @var \League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';

require BASE_PATH . '/bootstrap/bootstrap.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);


