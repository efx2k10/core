<?php

namespace Efx\Core\Dbal;

abstract class Model
{
    abstract function setId(int $id): void;

}