<?php

namespace Efx\Core\Dbal\Event;

use Efx\Core\Dbal\Model;
use Efx\Core\Event\Event;

class ModelPersist extends Event
{
    public function __construct(
        private Model $model,
    )
    {
    }

    public function getModel(): Model
    {
        return $this->model;
    }

}