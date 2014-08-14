<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Core\AbstractFactory;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $options = $this->validateOptions($options);

        $context = new \ZMQContext();

        $pushSocket = new SocketWrapper($context->getSocket(\ZMQ::SOCKET_PUSH), $options['scheme'], $options['host'], $options['port']);
        $pullSocket = new SocketWrapper($context->getSocket(\ZMQ::SOCKET_PULL), $options['scheme'], $options['host'], $options['port']);

        $transport = new Transport($pushSocket, $pullSocket);

        return $transport;
    }

    protected function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 5555
        );
    }

    protected function getOptionKeys()
    {
        return array(
            'scheme',
            'host',
            'port'
        );
    }
}
