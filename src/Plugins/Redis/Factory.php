<?php

namespace Aztech\Events\Bus\Plugins\Redis;

use Aztech\Events\Bus\AbstractFactory;
use Predis\Client;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $options = $this->validateOptions($options);
        $redis = new Client($options);
        $redis->connect();

        if (isset($options['password']) && ! empty($options['password'])) {
            $redis->auth($options['password']);
        }

        $transport = new Transport($redis, $options['event-key']);

        return $transport;
    }

    protected function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'event-key' => 'aztech:events:queue',
            'password' => null
        );
    }

    protected function getOptionKeys()
    {
        return array(
            'scheme',
            'host',
            'port',
            'event-key',
            'password'
        );
    }
}
