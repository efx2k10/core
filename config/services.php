<?php

use APP\Services\UserService;
use Doctrine\DBAL\Connection;
use Efx\Core\Auth\SessionAuthentication;
use Efx\Core\Auth\SessionAuthInterface;
use Efx\Core\Console\Application;
use Efx\Core\Console\Commands\MigrateCommand;
use Efx\Core\Controller\AbstractController;
use Efx\Core\Dbal\ConnectionFactory;
use Efx\Core\Event\EventDispatcher;
use Efx\Core\Http\Middleware\ExtractRouteInfoMiddleware;
use Efx\Core\Http\Middleware\RequestHandler;
use Efx\Core\Http\Middleware\RequestHandlerInterface;
use Efx\Core\Http\Middleware\RouterDispatchMiddleware;
use Efx\Core\Session\Session;
use Efx\Core\Session\SessionInterface;
use Efx\Core\Template\TwigFactory;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use Efx\Core\Http\Kernel;
use Efx\Core\Routing\Router;
use Efx\Core\Routing\RouterInterface;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Efx\Core\Console\Kernel as ConsoleKernel;

// APP Setting section

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');
$APP_ENV = $_ENV['APP_ENV'] ?? "dev";
$BASE_PATH = dirname(__DIR__);

$connectionParams = [
    'dbname' => $_ENV['DB_BASE'] ?? 'base',
    'port' => $_ENV['DB_PORT'] ?? 3306,
    'user' => $_ENV['DB_USER'] ?? 'user',
    'password' => $_ENV['DB_PSW'] ?? 'user',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'driver' => 'pdo_mysql'
];

$routes = include $BASE_PATH . '/routes/web.php';

$viewsPath = $BASE_PATH . '/views';

$consoleNameSpace = 'Efx\\Fw\\Console\\Commands\\';

// APP Container section

$container = new Container();
$container->delegate(new ReflectionContainer());

$container->add("APP_ENV", new StringArgument($APP_ENV));

$container->add("BASE_PATH", new StringArgument($BASE_PATH));

$container->add(RouterInterface::class, Router::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->addShared(EventDispatcher::class);

$container->add(Kernel::class)
    ->addArgument($container)
    ->addArgument(RequestHandlerInterface::class)
    ->addArgument(EventDispatcher::class);


$container->addShared(SessionInterface::class, Session::class);

// twig
$container->add('twig-factory', TwigFactory::class)
    ->addArgument(new StringArgument($viewsPath))
    ->addArgument(SessionInterface::class)
    ->addArgument(SessionAuthInterface::class);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});


$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new ArrayArgument($connectionParams));

$container->addShared(Connection::class, function () use ($container) {
    return $container->get(ConnectionFactory::class)->create();
});

// console
$container->add(Application::class)
    ->addArgument($container);

$container->add('fw-console-commands-namespace', new StringArgument($consoleNameSpace));

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

// console command migrate
$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)->addArgument(new StringArgument($BASE_PATH . '/database/migrations'));


// middleware
$container->add(RouterDispatchMiddleware::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);


// user session auth
$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArgument(UserService::class)
    ->addArgument(SessionInterface::class);

// middleware ExtractRouteInfoMiddleware
$container->add(ExtractRouteInfoMiddleware::class)
    ->addArgument(new ArrayArgument($routes));


return $container;
