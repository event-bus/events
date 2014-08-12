<?php

namespace Evaneos\Events\Providers\Simple;

class NullDispatcher implements EventDispatcher
{

    private $verbose = false;

    public function __construct($verbose = false)
    {
        $this->verbose = (bool)$verbose;
    }

    public function addListener($category, EventSubscriber $subscriber)
    {
        // Do nothing
    }

    public function dispatch(Event $event)
    {
        // Do nothing
    }
}
