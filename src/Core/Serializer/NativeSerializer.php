<?php

namespace Aztech\Events\Core\Serializer;

use Aztech\Events\Serializer;
use Aztech\Events\Event;

class NativeSerializer implements Serializer
{

    public function serialize(Event $object)
    {
        return serialize($object);
    }

    public function deserialize($object)
    {
        return unserialize($object);
    }
}
