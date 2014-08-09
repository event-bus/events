<?php

namespace Evaneos\Events\Publishers;

use Evaneos\Events\Event;
use Evaneos\Events\Queue\EventQueueManager;

class SynchronousEventPublisher implements \Evaneos\Events\EventPublisher
{
    /**
     *
     * @var \Evaneos\Events\EventDispatcher
     */
    private $dispatcher;

    /**
     *
     * @var EventQueueManager
     */
    private $queueManager;

    public function __construct(\Evaneos\Events\EventDispatcher $dispatcher, EventQueueManager $queueManager = null)
    {
        $this->dispatcher = $dispatcher;
        $this->queueManager = $queueManager;
    }

    public function publish(Event $event)
    {
        if ($this->queueManager != null) {
            $this->queueManager->addToQueue($event);
        }

        return $this->dispatcher->dispatch($event);
    }
}
