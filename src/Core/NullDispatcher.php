<?php

namespace Aztech\Events\Core;

use Aztech\Events\Subscriber;

class NullDispatcher implements \Aztech\Events\Dispatcher
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

    public function dispatch(\Aztech\Events\Event $event)
    {
        // Do nothing
    }
}
