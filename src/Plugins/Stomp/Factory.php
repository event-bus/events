<?php

namespace Aztech\Events\Bus\Plugins\Stomp;

use Aztech\Events\Bus\AbstractFactory;
use FuseSource\Stomp\Stomp;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $options = $this->validateOptions($options);

        $brokerUri = sprintf('%s://%s:%s', $options['scheme'], $options['host'], $options['port']);
        $client = new Stomp($brokerUri);

        $transport = new Transport($client, $options['queue']);

        return $transport;
    }

    protected function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'queue' => '/queue/aztech-events'
        );
    }

    protected function getOptionKeys()
    {
        return array(
            'scheme',
            'host',
            'port',
            'queue'
        );
    }
}
