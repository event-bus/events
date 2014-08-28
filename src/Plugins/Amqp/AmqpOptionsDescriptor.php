<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use Aztech\Events\Bus\Factory\OptionsDescriptor;

class AmqpOptionsDescriptor implements OptionsDescriptor
{
    public function getOptionKeys()
    {
        return array(
            'host',
            'port',
            'user',
            'pass',
            'vhost',
            'exchange',
            'event-queue',
            'event-prefix',
            'auto-create'
        );
    }

    public function getOptionDefaults()
    {
        return array(
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'guest',
            'pass' => 'guest',
            'vhost' => '/',
            'exchange' => 'aztech.events',
            'event-queue' => 'events',
            'event-prefix' => '',
            'auto-create' => true
        );
    }
}
