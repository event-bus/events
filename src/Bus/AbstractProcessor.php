<?php

namespace Aztech\Events\Bus;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Aztech\Events\Bus\Processor;
use Aztech\Events\Dispatcher;
use Aztech\Events\EventDispatcher;
use Aztech\Events\Subscriber;

/**
 * @todo Refactor status events dispatch to external class
 * @author thibaud
 *
 */
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
    }

    protected function onError(\Aztech\Events\Event $event,\Exception $ex)
    {
    }

    protected function onProcessing(\Aztech\Events\Event $event)
    {
    }

    protected function onProcessed(\Aztech\Events\Event $event)
    {
    }

    private function raise(\Aztech\Events\Event $event)
    {
        $this->dispatcher->dispatch($event);
    }
}
