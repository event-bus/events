<?php

namespace Evaneos\Events\Factory;

use Evaneos\Events\Publishers\RabbitMQ\RabbitMQEventPublisher;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Factory
{

    public static function createPublisher($type, $serializer, array $options = array()) {
        if ($type == 'rabbit') {
            $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
            $channel = $connection->channel();

            return new RabbitMQEventPublisher($channel, $options['exchange'], $serializer);
        }
    }

}
