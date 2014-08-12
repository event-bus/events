<?php

namespace Evaneos\Events\Factory;

use Evaneos\Events\EventSerializer;
use Evaneos\Events\Publishers\RabbitMQ\RabbitMQEventPublisher;
use Evaneos\Events\Publishers\Stomp\EventPublisher as StompEventPublisher;
use Evaneos\Events\Processors\RabbitMQ\RabbitMQEventProcessor;
use Evaneos\Events\Processors\RabbitMQ\RabbitMQEventStatusNotifier;
use Evaneos\Events\SimpleDispatcher;
use Evaneos\Events\Subscribers\PublishingSubscriber;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Evaneos\Events\SimpleConsumer;

class RabbitMqFactory implements Factory
{

    private $serializer;

    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    private function validateOptions(array $options)
    {
        $keys = array('host', 'port', 'user', 'pass', 'vhost');
        $defaults = array('port' => 5672, 'vhost' => '/');

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

    public function createPublisher(array $options = array())
    {
        $options = $this->validateOptions($options);

        $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
        $channel = $connection->channel();

        return new RabbitMQEventPublisher($channel, $options['exchange'], $this->serializer);
    }

    public function createDispatcher(array $options = array())
    {
        $dispatcher = new SimpleDispatcher();
        $publisher = new EventPublisher($this->serializer);

        $dispatcher->addListener('*', new PublishingSubscriber($publisher));

        return $dispatcher;
    }


    public function createConsumer(array $options = array())
    {
        return new SimpleConsumer($this->createProcessor($options), $this->createDispatcher($options));
    }

    public function createProcessor(array $options = array())
    {
        $options = $this->validateOptions($options);

        $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
        $channel = $connection->channel();

        $processor = new RabbitMQEventProcessor($channel, $options['event-queue'], $this->serializer);

        if (isset($options['event-status-queue'])) {
            $options['event-queue'] = $options['event-status-queue'];
            unset($options['event-status-queue']);

            $publisher = $this->createPublisher($options);

            $processor->on('*', new RabbitMQEventStatusNotifier($publisher));
        }

        return $processor;
    }
}
