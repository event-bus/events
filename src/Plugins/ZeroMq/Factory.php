<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Core\AbstractFactory;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $options = $this->validateOptions($options);

        $context = new \ZMQContext();

        $pushType = $options['multicast'] ? \ZMQ::SOCKET_ROUTER : \ZMQ::SOCKET_PUSH;
        $pullType = $options['multicast'] ? \ZMQ::SOCKET_DEALER : \ZMQ::SOCKET_PULL;

        $pushSocket = $context->getSocket($pushType);
        $pullSocket = $context->getSocket($pullType);

        $pushHost = $options['multicast'] ? $options['host'] : $options['host'];
        $pullHost = $options['multicast'] ? $options['host'] : $options['host'];

        if (! $options['multicast']) {
            $pushDsn = sprintf('%s://%s:%s', $options['scheme'], $pushHost, $options['port']);
            $pullDsn = sprintf('%s://%s:%s', $options['scheme'], $pullHost, $options['port']);
        }
        else {
            $pullDsn = $pushDsn = ('ipc://routing.ipc');
        }

        $pushWrapper = new SocketWrapper($pushSocket, $pushDsn, $options['multicast']);
        $pullWrapper = new SocketWrapper($pullSocket, $pullDsn, ! $options['multicast']);

        $transport = new Transport($pushWrapper, $pullWrapper, $options['multicast']);

        return $transport;
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
