<?php

namespace Aztech\Events\Bus;

use Aztech\Events\EventDispatcher;
use Aztech\Events\Factory;
use Aztech\Events\Bus\Publisher\TransportPublisher;
use Aztech\Events\Bus\Processor\TransportProcessor;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractFactory implements Factory, LoggerAwareInterface
{

    protected $serializer;

    protected $logger;

    protected abstract function createTransport(array $options);

    public function __construct(\Aztech\Events\Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function createConsumer(array $options = array())
    {
        return new Consumer($this->createProcessor($options), new EventDispatcher());
    }

    public function createProcessor(array $options = array())
    {
        $options = $this->validateOptions($options);
        $transport = $this->createTransport($options);
        $processor = new TransportProcessor($transport, $this->serializer);

        return $processor;
    }

    public function createPublisher(array $options = array())
    {
        $options = $this->validateOptions($options);
        $transport = $this->createTransport($options);

        return new TransportPublisher($transport, $this->serializer);
    }

    protected function validateOptions(array $options)
    {
        $keys = $this->getOptionKeys();
        $defaults = $this->getOptionDefaults();

        $actual = array();

        foreach ($keys as $key) {
            if (! array_key_exists($key, $options) && ! array_key_exists($key, $defaults)) {
                throw new \InvalidArgumentException('Options key ' . $key . ' is required in config.');
            }
            elseif (! array_key_exists($key, $options)) {
                $value = $defaults[$key];
            }
            else {
                $value = $options[$key];
            }

            $actual[$key] = $value;
        }

        return $actual;
    }

    protected function getOptionKeys()
    {
        return array();
    }

    protected function getOptionDefaults()
    {
        return array();
    }
}
