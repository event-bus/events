<?php

namespace Aztech\Events\Bus\Plugins\Redis;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class RedisOptionsDescriptor implements OptionsDescriptor
{
    public function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'event-key' => 'aztech:events:queue',
            'password' => null
        );
    }

    public function getOptionKeys()
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
