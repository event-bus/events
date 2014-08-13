<?php

namespace Aztech\Events;

interface Serializer
{

    public function serialize(Event $object);

    /**
     * @return Event
     */
    public function deserialize($serializedObject);

}
