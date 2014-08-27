<?php

namespace Aztech\Events\Bus\Plugins\Pdo;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class PdoOptionsDescriptor implements OptionsDescriptor
{

    public function getOptionKeys()
    {
        return array(
            'driver',
            'host',
            'port',
            'user',
            'pass',
            'database',
            'table',
            'id-column',
            'data-column'
        );
    }

    public function getOptionDefaults()
    {
        return array(
            'driver'  => 'mysql',
            'port' => 3306,
            'host' => '127.0.0.1',
            'table' => 'events',
            'id-column' => 'id',
            'data-column' => 'data'
        );
    }
}
