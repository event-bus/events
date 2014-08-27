<?php

namespace Aztech\Events\Bus\Serializer;

use Aztech\Events\Bus\Serializer;
use Aztech\Events\Event;
use DrSlump\Protobuf\CodecInterface;

class ProtobufSerializer implements Serializer
{

    private $codec;

    private $hydratedClassName;

    public function __construct(CodecInterface $codec, $hydratedClassName)
    {
        $this->codec = $codec;
        $this->hydratedClassName = $hydratedClassName;
    }

    public function serialize(Event $event)
    {
        if (! ($event instanceof $this->hydratedClassName)) {
            throw new \BadMethodCallException();
        }

        return $this->codec->encode($event);
    }

    public function deserialize($value)
    {
        $reflectionClass = new \ReflectionClass($this->hydratedClassName);
        $message = $reflectionClass->newInstanceWithoutConstructor();

        $this->codec->decode($message, $value);

        return $message;
    }
}
