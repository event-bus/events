<?php

namespace Aztech\Events\Bus\Plugins\Stomp;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class StompOptionsDescriptor implements OptionsDescriptor
{

    public function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'queue' => '/queue/aztech-events'
        );
    }

    public function getOptionKeys()
    {
        return array(
            'scheme',
            'host',
            'port',
            'queue'
        );
    }
}
