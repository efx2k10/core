<?php

namespace Efx\Core\Console;

interface CommandInterface
{
    public function execute(array $params = []): int;
}