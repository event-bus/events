<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Bus\AbstractFactory;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $options = $this->validateOptions($options);

        $context = new \ZMQContext();

        $pushWrapper = $this->createPushSocketWrapper($context, $options);
        $pullWrapper = $this->createPullSocketWrapper($context, $options);

        $transport = new PubSubTransport($pushWrapper, $pullWrapper, $this->logger);

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
        $options['host'] = $options['push-host'];
        return $this->createSocketWrapper($context, $options, \ZMQ::SOCKET_PUB, true);
    }

    private function createPullSocketWrapper($context, $options)
    {
        $options['host'] = $options['pull-host'];

        $wrapper = $this->createSocketWrapper($context, $options, \ZMQ::SOCKET_SUB, false);
        $wrapper->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, '');

        return $wrapper;
    }

    private function createSocketWrapper($context, $options, $socketType, $forceBind)
    {
        $dsn = $this->getDsn($options);
        $type = $socketType;

        $socket = new \ZMQSocket($context, $type);

        if ($options['multicast']) {
            $dsn = ('ipc://routing.ipc');
        }

        return new SocketWrapper($socket, $dsn, $this->logger);
    }

    protected function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'pull-host' => '127.0.0.1',
            'push-host' => '127.0.0.1',
            'port' => 5563,
            'multicast' => false
        );
    }

    protected function getOptionKeys()
    {
        return array(
            'scheme',
            'push-host',
            'pull-host',
            'port',
            'multicast'
        );
    }
}
