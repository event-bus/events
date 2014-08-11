<?php

namespace Evaneos\Events\Processors;

use Evaneos\Events\EventProcessor;
use Evaneos\Events\Event;
use Evaneos\Events\EventSubscriber;
use Evaneos\Events\StandardDispatcher;
use Evaneos\Events\StatusEvent;
use Evaneos\Events\EventDispatcher;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractProcessor implements EventProcessor, LoggerAwareInterface
{

    protected $dispatcher;

    protected $logger;

    public function __construct()
    {
        $this->dispatcher = new StandardDispatcher();
        $this->setLogger(new NullLogger());
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->dispatcher->setLogger($logger);
    }

    public function on($categoryFilter, EventSubscriber $subscriber)
    {
        $this->logger->debug('Binding "' . get_class($subscriber) . '" instance to filter "' . $categoryFilter . '"');
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
        $this->logger->debug('Raising event : ' . $event->getCategory());
        $this->dispatcher->dispatch($event);
    }
}
