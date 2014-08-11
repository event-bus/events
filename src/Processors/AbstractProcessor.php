<?php

namespace Evaneos\Events\Processors;

use Evaneos\Events\EventProcessor;
use Evaneos\Events\Event;
use Evaneos\Events\EventSubscriber;
use Evaneos\Events\StandardDispatcher;
use Evaneos\Events\StatusEvent;

abstract class AbstractProcessor implements EventProcessor
{

    protected $dispatcher;

    public function __construct()
    {
        $this->dispatcher = new StandardDispatcher();
    }

    public function on($categoryFilter, EventSubscriber $subscriber)
    {
        $this->dispatcher->addListener($categoryFilter, $subscriber);
    }

    protected function onShutdown()
    {
        $status = new StatusEvent(self::EVENT_NODE_STOP, null);

        $this->raise($status);
    }

    protected function onError(Event $event,\Exception $ex)
    {
        $status = new StatusEvent(self::EVENT_ERROR, $event);

        $this->raise($status);
    }

    protected function onProcessing(Event $event)
    {
        $status = new StatusEvent(self::EVENT_PROCESSING, $event);

        $this->raise($status);
    }

    protected function onProcessed(Event $event)
    {
        $status = new StatusEvent(self::EVENT_PROCESSED, $event);

        $this->raise($status);
    }

    private function raise(Event $event)
    {
        $this->dispatcher->dispatch($event);
    }
}
