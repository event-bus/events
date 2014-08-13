<?php

namespace Aztech\Events\Core;

use Aztech\Events\Factory;

abstract class AbstractFactory implements Factory
{

    protected abstract function createTransport(array $options);

    protected abstract function validateOptions(array $options);

    protected $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createConsumer(array $options = array())
    {
        return new Consumer($this->createProcessor($options), new Dispatcher());
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
