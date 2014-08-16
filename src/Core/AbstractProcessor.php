<?php

namespace Aztech\Events\Core;

use Aztech\Events\Processor;
use Aztech\Events\Subscriber;
use Aztech\Events\EventDispatcher as Dispatcher;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
        $this->dispatcher = new Dispatcher();
        $this->setLogger(new NullLogger());
    }

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
        /*$status = new StatusEvent(self::EVENT_NODE_STOP, null);

        $this->raise($status);*/
    }

    protected function onError(\Aztech\Events\Event $event,\Exception $ex)
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
        /*if ($event instanceof StatusEvent && $event->getEvent()) {
            $this->logger->debug('[ "' . $event->getEvent()->getId() . '" ] Raising status event "' . $event->getId() . '"');
        }*/

        $this->dispatcher->dispatch($event);
    }
}
