<?php

namespace Aztech\Events\Core;

use Aztech\Events\Util\TrieMatcher\Trie;
use Aztech\Events\Event;

class Serializer implements \Aztech\Events\Serializer
{

    private $serializationMap = array();

    public function bindSerializer($eventFilter, \Aztech\Events\Serializer $serializer)
    {
        if ($serializer === $this) {
            throw new \InvalidArgumentException('Cannot bind to self, infinite recursion ahead.');
        }

        array_unshift($this->serializationMap, array(
            new Trie($eventFilter),
            $serializer
        ));
    }

    public function getSerializer($category)
    {
        foreach ($this->serializationMap as $matcher) {
            $trie = $matcher[0];
            $serializer = $matcher[1];

            if ($trie->matches($category)) {
                return $serializer;
            }
        }

        throw new \OutOfBoundsException('No matching serializers : ' . $category);
    }

    public function serialize(Event $object)
    {
        $serializer = $this->getSerializer($object->getCategory());

        return $serializer->serialize($object);
    }

    public function deserialize($serializedObject)
    {
        // Fix me ! Makes the serializer only compatible with Json serializer
        $deserialized = json_decode($serializedObject);

        if (! $deserialized) {
            return null;
        }

        $serializer = $this->getSerializer($deserialized->category);

        return $serializer->deserialize($serializedObject);
    }
}
