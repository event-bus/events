<?php

namespace Evaneos\Events\Subscribers;

use Evaneos\Events\EventSubscriber;
use Evaneos\Events\Event;

class CallbackSubscriber implements EventSubscriber
{

    private $callback;

    public function __construct($callable)
    {
        if (! is_callable($callable)) {
            throw new \InvalidArgumentException('Not a callback.');
        }

        $this->callback = $callable;
    }

    public function supports(Event $event)
    {
        return true;
    }

    public function handle(Event $event)
    {
        $callback = $this->callback;

        $callback($event);
    }
}
