<?php

namespace Evaneos\Events;

interface Serializer
{

    public function serialize($object);

    public function deserialize($serializedObject);
}
