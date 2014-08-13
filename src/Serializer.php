<?php

namespace Aztech\Events;

interface Serializer
{

    public function serialize(Event $object);

    public function deserialize($serializedObject);

}
