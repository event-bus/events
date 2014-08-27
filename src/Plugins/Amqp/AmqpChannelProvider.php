<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class AmqpChannelProvider implements ChannelProvider
{
    function createChannel(array $options = array())
    {
        $amqpConnection = new AMQPStreamConnection($options['host'], $options['port'], $options['user'], $options['pass'], $options['vhost']);
        $amqpChannel = $connection->channel();

        $topologyBuilder = new AmqpTopologyBuilder($amqpChannel, $options['exchange'], $options['event-queue'], $options['event-prefix']);
        $topologyBuilder->build();

        $reader = new AmqpChannelReader($amqpChannel, $options['exchange'], $options['event-queue']);
        $writer = new AmqpChannelWriter($amqpChannel, $options['exchange'], $options['event-prefix']);

        return new ReadWriteChannel($reader, $writer);
    }
}
