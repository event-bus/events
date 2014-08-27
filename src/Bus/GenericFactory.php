<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Transport\TransportProvider;
use Aztech\Events\Bus\Publisher\TransportPublisher;

class GenericFactory implements Factory
{

    protected $options = array();

    protected $defaults = array();

    protected $serializer;

    protected $logger;

    protected $transportProvider;

    public function __construct(Aztech\Events\Bus\Serializer $serializer,
        TransportProvider $transportProvider,
        array $optionKeys = array(),
        array $optionDefaults = array())
    {
        $this->serializer = $serializer;
        $this->transportProvider = $transportProvider;

        $this->options = $optionKeys;
        $this->defaults = $defaults;
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
        return $this->options;
    }

    protected function getOptionDefaults()
    {
        return $this->defaults;
    }
}
