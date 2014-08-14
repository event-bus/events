<?php

namespace Aztech\Events\Plugins\Amqp;

use Aztech\Events\Core\AbstractFactory;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $connection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
        $channel = $connection->channel();

        $transport = new Transport($channel, $options['exchange'], $options['event-queue']);
        $transport->setLogger($this->logger);

        return $transport;
    }

    protected function getOptionKeys()
    {
        return array(
            'host',
            'port',
            'user',
            'pass',
            'vhost',
            'exchange',
            'event-queue'
        );
    }

    protected function getOptionDefaults()
    {
        return array(
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'guest',
            'pass' => 'guest',
            'vhost' => '/',
            'exchange' => 'aztech.events',
            'event-queue' => 'events'
        );
    }
}
