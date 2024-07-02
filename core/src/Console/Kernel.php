<?php

namespace Efx\Core\Console;

use Psr\Container\ContainerInterface;

class Kernel
{

    public function __construct(
        private ContainerInterface $container,
        private Application        $application
    )
    {
    }

    public function handle(): int
    {
        $this->registerCommands();
        $status = $this->application->run();


        return $status;
    }

    private function registerCommands()
    {

        $commandFiles = new \DirectoryIterator(__DIR__ . '/Commands');
        $consoleNameSpace = $this->container->get('fw-console-commands-namespace');

        foreach ($commandFiles as $commandFile) {
            if (!$commandFile->isFile()) continue;
            $fileName = pathinfo($commandFile, PATHINFO_FILENAME);
            $command = $consoleNameSpace . $fileName;

            if (!is_subclass_of($command, CommandInterface::class)) continue;

            $reflex = (new \ReflectionClass($command));

            $name = $reflex->hasProperty('name')
                ? $reflex->getProperty('name')->getDefaultValue()
                : strtok(strtolower($fileName), 'command');

            $this->container->add('console:' . $name, $command);
        }
    }
}