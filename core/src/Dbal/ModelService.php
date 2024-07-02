<?php

namespace Efx\Core\Dbal;

use Doctrine\DBAL\Connection;
use Efx\Core\Dbal\Event\ModelPersist;
use Efx\Core\Event\EventDispatcher;

class ModelService
{
    public function __construct(
        private Connection      $connection,
        private EventDispatcher $eventDispatcher,

    )
    {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function save(Model $model): int
    {
        $id = $this->connection->lastInsertId();
        $model->setId($id);
        $this->eventDispatcher->dispatch(new ModelPersist($model));

        return $id;
    }
}