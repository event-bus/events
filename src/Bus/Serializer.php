<?php

namespace Aztech\Events\Bus;

use \Aztech\Events\Event as EventInterface;

interface Serializer
{

    public function serialize(EventInterface $object);

    /**
     *
     * @return Event
     */
    public function deserialize($serializedObject);
}
