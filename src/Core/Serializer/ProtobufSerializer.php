<?php

namespace Aztech\Events\Core\Serializer;

use Aztech\Events\Serializer;
use Aztech\Events\Event;
use DrSlump\Protobuf\CodecInterface;
use DrSlump\Protobuf\MessageInterface;

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

    public function unserialize($value)
    {
        $reflectionClass = new \ReflectionClass($this->hydratedClassName);
        $message = $reflectionClass->newInstanceWithoutConstructor();

        $this->codec->decode($message, $value);

        return $message;
    }

}
