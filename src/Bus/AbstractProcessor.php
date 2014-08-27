<?php

namespace Aztech\Events\Bus;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Aztech\Events\Bus\Processor;
use Aztech\Events\Dispatcher;
use Aztech\Events\Event;
use Aztech\Events\EventDispatcher;
use Aztech\Events\Subscriber;

abstract class AbstractProcessor implements Processor, LoggerAwareInterface
{

    private $dispatcher;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
        $this->setLogger(new NullLogger());
    }

    abstract public function processNext(Dispatcher $dispatcher);

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->dispatcher->setLogger($logger);
    }

    public function on($categoryFilter, Subscriber $subscriber)
    {
        $this->logger->debug('Binding "' . get_class($subscriber) . '" instance to filter "' . $categoryFilter . '"');
        $this->dispatcher->addListener($categoryFilter, $subscriber);
    }

    protected function onShutdown()
    {
        /*$status = new Event(self::EVENT_NODE_STOP, null);

        $this->raise($status);*/
    }

    protected function onError(Event $event,\Exception $ex)
    {
        /*$status = new StatusEvent(self::EVENT_ERROR, $event);

        $this->raise($status);*/
    }

    protected function onProcessing(\Aztech\Events\Event $event)
    {
        /*$status = new StatusEvent(self::EVENT_PROCESSING, $event);

        $this->raise($status);*/
    }

    protected function onProcessed(\Aztech\Events\Event $event)
    {
        /*$status = new StatusEvent(self::EVENT_PROCESSED, $event);

        $this->raise($status);*/
    }

    private function raise(\Aztech\Events\Event $event)
    {
        if ($event instanceof StatusEvent && $event->getEvent()) {
            $this->logger->debug('[ "' . $event->getEvent()->getId() . '" ] Raising status event "' . $event->getId() . '"');
        }

        $this->dispatcher->dispatch($event);
    }
}
