<?php

namespace Aztech\Events\Core;

use Aztech\Events\Event;
use Aztech\Events\Transport;

class MemoryTransport implements Transport
{

    private $events;

    public function __construct()
    {
        $this->events = new \SplStack();
    }

    public function write(Event $event, $serializedRepresentation)
    {
        $this->events->push($event);
    }

    public function read()
    {
        while (! $this->events->count()) {
            usleep(250000);
        }

        if ($this->events->count()) {
            return $this->events->pop();
        }
    }
}
