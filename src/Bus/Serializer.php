<?php

namespace Aztech\Events\Bus;

interface Serializer
{

    public function serialize(\Aztech\Events\Event $object);

    /**
     *
     * @return Event
     */
    public function deserialize($serializedObject);
}
