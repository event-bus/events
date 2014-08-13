<?php

namespace Aztech\Events\Core;

use Aztech\Events\Dispatcher;
use Aztech\Events\Event;
use Aztech\Events\Subscriber;

class NullDispatcher implements Dispatcher
{

    private $verbose = false;

    public function __construct($verbose = false)
    {
        $this->verbose = (bool)$verbose;
    }

    public function addListener($category, Subscriber $subscriber)
    {
        // Do nothing
    }

    public function dispatch(Event $event)
    {
        // Do nothing
    }
}
