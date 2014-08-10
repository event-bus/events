<?php

namespace Evaneos\Events;

class NullDispatcher implements EventDispatcher
{

    private $verbose = false;

    public function __construct($verbose = false)
    {
        $this->verbose = (bool)$verbose;
    }

    public function addListener($category, EventSubscriber $subscriber)
    {
        // Do nothing ! As my name implies....
    }

    public function dispatch(Event $event)
    {
        // Do... guess ? Nothing ! Ok, maybe just mention we got an event...
        if ($this->verbose) {
            echo 'Got an event : ' . $event->getCategory() . ', yeah !', PHP_EOL;
        }
    }
}
