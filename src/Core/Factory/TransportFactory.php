<?php

namespace Aztech\Events\Core\Factory;

use Aztech\Events\Transport;
use Aztech\Events\Serializer;
use Aztech\Events\Core\Publisher\SynchronousPublisher;
use Aztech\Events\Core\Dispatcher;
use Aztech\Events\Core\Publisher\TransportPublisher;
use Aztech\Events\Core\Processor\TransportProcessor;
use Aztech\Events\Core\Consumer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TransportFactory implements \Aztech\Events\Factory
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
        $this->logger = $logger ?: new NullLogger();
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

        $consumer = new Consumer($processor, $dispatcher);

        return $consumer;
    }

}
