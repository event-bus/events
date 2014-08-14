<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Core\AbstractFactory;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $options = $this->validateOptions($options);

        $context = new \ZMQContext();

        $pushWrapper = $this->createPushSocketWrapper($context, $options);
        $pullWrapper = $this->createPullSocketWrapper($context, $options);

        $transport = new Transport($pushWrapper, $pullWrapper, $options['multicast']);

        return $transport;
    }

    private function getDsn($options)
    {
        $dsn = sprintf('%s://%s:%s', $options['scheme'], $options['host'], $options['port']);

        if ($options['multicast']) {
            $dsn = ('ipc://routing.ipc');
        }

        return $dsn;
    }

    private function createPushSocketWrapper($context, $options)
    {
        return $this->createSocketWrapper($context, $options, \ZMQ::SOCKET_ROUTER, \ZMQ::SOCKET_PUSH);
    }

    private function createPullSocketWrapper($context, $options)
    {
        return $this->createSocketWrapper($context, $options, \ZMQ::SOCKET_DEALER, \ZMQ::SOCKET_PULL);
    }

    private function createSocketWrapper($context, $options, $multicastSocketType, $socketType)
    {
        $dsn = $this->getDsn($options);
        $type = $options['multicast'] ? $multicastSocketType : $socketType;

        $socket = new \ZMQSocket($context, $type);

        if ($options['multicast']) {
            $dsn = ('ipc://routing.ipc');
        }

        return new SocketWrapper($socket, $dsn, $options['multicast']);
    }

    protected function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 5563,
            'multicast' => false
        );
    }

    protected function getOptionKeys()
    {
        return array(
            'scheme',
            'host',
            'port',
            'multicast'
        );
    }
}
