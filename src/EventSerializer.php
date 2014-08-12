<?php

namespace Evaneos\Events;

class EventSerializer
{

    private $serializationMap = array();

    public function bindSerializer($eventCategory, Serializer $serializer)
    {
        if ($serializer === $this) {
            throw new \InvalidArgumentException('Cannot bind to self, infinite recursion ahead.');
        }

        $this->serializationMap[$eventCategory] = $serializer;
    }

    public function getSerializer($category)
    {
        if (! array_key_exists($category, $this->serializationMap)) {
            throw new \OutOfBoundsException('Unknown serialization key : ' . $category);
        }

        return $this->serializationMap[$category];
    }

    public function serialize(Event $object)
    {
        $serializer = $this->getSerializer($object->getCategory());

        return $serializer->serialize($object);
    }

    public function deserialize($serializedObject)
    {
        $deserialized = json_decode($serializedObject);

        if (! $deserialized) {
            return null;
        }

        $serializer = $this->getSerializer($deserialized->category);

        return $serializer->deserialize($serializedObject);
    }
}
