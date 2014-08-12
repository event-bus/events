<?php

namespace Evaneos\Events;

use Evaneos\Events\Factory\RabbitMqFactory;
use Evaneos\Events\Factory\WampFactory;
use Evaneos\Events\Providers\Simple\SimpleEvent;

class Events
{

    public static function create($name, array $properties = array())
    {
        return new SimpleEvent($name, $properties);
    }

    public static function createAmqpFactory(Serializer $serializer = null)
    {
        return new RabbitMqFactory($serializer ?: new SimpleEventSerializer());
    }

    public static function createRedisFactory(Serializer $serializer = null)
    {
        return new RedisFactory($serializer ?: new SimpleEventSerializer());
    }

    public static function createWampFactory(Serializer $serializer = null)
    {
        return new WampFactory($serializer ?: new SimpleEventSerializer());
    }

    public static function createSimpleFactory(Serializer $serializer = null)
    {
        return new SimpleFactory($serializer ?: new SimpleEventSerializer());
    }

}
