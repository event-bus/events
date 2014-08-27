<?php

namespace Aztech\Events\Bus\Plugins\Socket;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class SocketOptionsDescriptor implements OptionsDescriptor
{

    public function getOptionKeys()
    {
        return array(
            'protocol',
            'host',
            'port'
        );
    }

    public function getOptionDefaults()
    {
        return array(
            'protocol' => 'ipv4',
            'host' => '127.0.0.1',
            'port' => 8088
        );
    }
}
