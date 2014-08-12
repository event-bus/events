<?php

namespace Evaneos\Events;

use Evaneos\Events\EventProcessor;
use Evaneos\Events\Event;
use Evaneos\Events\EventSubscriber;
use Evaneos\Events\SimpleDispatcher;
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
        $this->dispatcher = new SimpleDispatcher();
        $this->setLogger(new NullLogger());
    }

    public abstract function processNext(EventDispatcher $dispatcher);

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->dispatcher->setLogger($logger);
    }

    public function on($categoryFilter, $subscriber)
    {
        if (! is_callable($subscriber) && ! ($subscriber instanceof EventSubscriber)) {
            throw new \InvalidArgumentException('Subscriber must be a callback or an instance of EventSubscriber.');
        }

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
        if ($event instanceof StatusEvent && $event->getEvent()) {
            $this->logger->debug('[ "' . $event->getEvent()->getId() . '" ] Raising status event "' . $event->getId() . '"');
        }

        $this->dispatcher->dispatch($event);
    }
}
