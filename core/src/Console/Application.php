<?php

namespace Efx\Core\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {

        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        if (!$commandName) throw new ConsoleException('Invalid console command');

        /** @var CommandInterface $command */
        $command = $this->container->get('console:' . $commandName);

        $params = $this->parseParams(array_slice($argv, 2));

        $status = $command->execute($params);

        return $status;
    }

    private function parseParams(array $args): array
    {
        $params = [];

        foreach ($args as $arg) {
            if (!str_starts_with($arg, '--')) continue;

            $param = explode('=', substr($arg, 2));
            $params[$param[0]] = $param[1] ?? true;
        }

        return $params;
    }
}