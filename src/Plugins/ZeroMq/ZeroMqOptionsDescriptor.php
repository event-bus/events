<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class ZeroMqOptionsDescriptor implements OptionsDescriptor
{
    public function getOptionDefaults()
    {
        return array(
            'scheme' => 'tcp',
            'publisher' => '127.0.0.1',
            'subscriber' => '127.0.0.1',
            'port' => 5563,
            'multicast' => false
        );
    }

    public function getOptionKeys()
    {
        return array(
            'scheme',
            'publisher',
            'subscriber',
            'port',
            'multicast'
        );
    }
}
