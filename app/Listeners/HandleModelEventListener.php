<?php

namespace APP\Listeners;

use Efx\Core\Dbal\Event\ModelPersist;

class HandleModelEventListener
{
    public function __invoke(ModelPersist $event): void
    {

    }

}