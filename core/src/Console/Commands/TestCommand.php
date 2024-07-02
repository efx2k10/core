<?php

namespace Efx\Core\Console\Commands;

use Efx\Core\Console\CommandInterface;

class TestCommand implements CommandInterface
{


    public function execute(array $params = []): int
    {
        echo "Hello World!\n";
        return 0;
    }
}