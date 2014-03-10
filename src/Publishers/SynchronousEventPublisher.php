<?php

namespace Evaneos\Events\Publishers;

use Evaneos\Events\Event;

class SynchronousEventPublisher implements \Evaneos\Events\EventPublisher
{
    /**
     *
     * @var \Evaneos\Events\EventDispatcher
     */
    private $dispatcher;

    public function __construct(\Evaneos\Events\EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function publish(Event $event)
    {
        $this->dispatcher->dispatch($event);
    }
}
