<?php

namespace Evaneos\Events;

use Evaneos\Events\Factory\RabbitMqFactory;

class Factory
{

    public static function createAmqpFactory(Serializer $serializer = null)
    {
        return new RabbitMqFactory($serializer ?: new SimpleEventSerializer());
    }

    public static function createStompFactory(Serializer $serializer = null)
    {
        return new \StompFactory($serializer ?: new SimpleEventSerializer());
    }

}
