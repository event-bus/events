<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Plugins\ZeroMq\Reader\SubscribeChannelReader;
use Aztech\Events\Bus\Plugins\ZeroMq\Writer\PublishChannelWriter;

class ZeroMqChannelProvider implements ChannelProvider
{

    public function createChannel(array $options)
    {
        $context = new \ZMQContext();

        $publisher = $this->createZmqPublisher($context, $options);
        $subscriber = $this->createZmqSubscriber($context, $options);

        $writer = new PublishChannelWriter($publisher);
        $reader = new SubscribeChannelReader($subscriber);

        return $transport;
    }

    private function getDsn($options)
    {
        $dsn = sprintf('%s://%s:%s', $options['scheme'], $options['host'], $options['port']);

        return $dsn;
    }

    private function createZmqPublisher($context, $options)
    {
        $options['host'] = $options['publisher'];

        return $this->createSocketWrapper($context, $options, \ZMQ::SOCKET_PUB, true);
    }

    private function createZmqSubscriber($context, $options)
    {
        $options['host'] = $options['subscriber'];

        $wrapper = $this->createSocketWrapper($context, $options, \ZMQ::SOCKET_SUB, false);
        $wrapper->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, '');

        return $wrapper;
    }

    private function createSocketWrapper($context, $options, $socketType)
    {
        $dsn = $this->getDsn($options);
        $type = $socketType;

        $socket = new \ZMQSocket($context, $type);

        return new ZeroMqSocketWrapper($socket, $dsn, $this->logger);
    }
}
