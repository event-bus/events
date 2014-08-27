<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Event as EventInterface;

interface Publisher
{

    public function publish(EventInterface $event);
}
