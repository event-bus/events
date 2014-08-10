<?php

namespace Evaneos\Events\Factory;

use Evaneos\Events\Publishers\RabbitMQ\RabbitMQEventPublisher;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Evaneos\Events\Processors\RabbitMQ\RabbitMQEventProcessor;
use Evaneos\Events\Processors\RabbitMQ\RabbitMQEventStatusNotifier;
use Evaneos\Events\StandardDispatcher;
use Evaneos\Events\Subscribers\PublishingSubscriber;
use Evaneos\Events\Publishers\Stomp\EventPublisher as StompEventPublisher;

class Factory
{

    public static function createPublisher($type, $serializer, array $options = array())
    {
        if ($type == 'rabbit') {
            $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
            $channel = $connection->channel();
            
            return new RabbitMQEventPublisher($channel, $options['exchange'], $serializer);
        }
    }

    public static function createProcessor($type, $serializer, array $options = array())
    {
        if ($type == 'rabbit') {
            $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
            $channel = $connection->channel();
            
            $processor = new RabbitMQEventProcessor($channel, $options['event-queue'], $serializer);
            
            if (isset($options['event-status-queue'])) {
                $options['event-queue'] = $options['event-status-queue'];
                
                $publisher = self::createPublisher($type, $serializer, $options);
                $processor->on('*', new RabbitMQEventStatusNotifier($publisher));
            }
            
            return $processor;
        }
    }

    public function createStatusProcessor($type, $serializer, array $options = array())
    {
        if ($type == 'rabbit') {
            $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
            $channel = $connection->channel();
            
            $processor = new RabbitMQEventProcessor($channel, $options['event-status-queue'], $serializer);
        }
    }

    public function createStompDispatcher($serializer)
    {
        $dispatcher = new StandardDispatcher();
        $publisher = new StompEventPublisher($serializer);
        
        $dispatcher->addListener('*', new PublishingSubscriber($publisher));
        
        return $dispatcher;
    }
}
