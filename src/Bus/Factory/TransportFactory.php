<?php

namespace Aztech\Events\Bus\Factory;

use Aztech\Events\Transport;
use Aztech\Events\Serializer;
use Aztech\Events\EventDispatcher as Dispatcher;
use Aztech\Events\Bus\Publisher\TransportPublisher;
use Aztech\Events\Bus\Processor\TransportProcessor;
use Aztech\Events\Bus\Consumer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Log\LoggerAwareInterface;

class TransportFactory implements \Aztech\Events\Factory, LoggerAwareInterface
{

    private $canConsume = true;

    private $canPublish = true;

    private $serializer;

    private $transport;

    private $logger;

    public function __construct(Transport $transport, Serializer $serializer, LoggerInterface $logger = null)
    {
        $this->serializer = $serializer;
        $this->transport = $transport;
        $this->logger = $logger ?  : new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function disablePublish()
    {
        $this->canPublish = false;
    }

    public function disableProcess()
    {
        $this->canConsume = false;
    }

    function createPublisher(array $options = array())
    {
        if (! $this->canPublish) {
            throw new \BadMethodCallException('Publish is not supported by this factory.');
        }

        $publisher = new TransportPublisher($this->transport, $this->serializer);

        return $publisher;
    }

    function createProcessor(array $options = array())
    {
        if (! $this->canConsume) {
            throw new \BadMethodCallException('Process is not supported by this factory.');
        }

        $processor = new TransportProcessor($this->transport, $this->serializer);
        $processor->setLogger($this->logger);

        return $processor;
    }

    function createConsumer(array $options = array())
    {
        if (! $this->canConsume) {
            throw new \BadMethodCallException('Consume is not supported by this factory.');
        }

        $dispatcher = new Dispatcher();
        $dispatcher->setLogger($this->logger);

        $processor = $this->createProcessor($options);

        return new Consumer($processor, $dispatcher);
    }
}
