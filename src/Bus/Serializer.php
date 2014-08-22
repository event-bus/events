<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Util\TrieMatcher\Trie;

class Serializer implements \Aztech\Events\Serializer
{

    private $serializationMap = array();

    public function bindSerializer($eventFilter, \Aztech\Events\Serializer $serializer)
    {
        if ($serializer === $this || ($serializer instanceof Serializer && $serializer->hasInChildren($this))) {
            throw new \InvalidArgumentException('Cannot bind to self or to serializer containing self, infinite recursion ahead.');
        }

        array_unshift($this->serializationMap, array(
            new Trie($eventFilter),
            $serializer
        ));
    }

    public function hasInChildren(Serializer $serializer)
    {
        foreach ($this->serializationMap as $serializationPair) {
            if ($serializationPair[1] === $serializer) {
                return true;
            }
            elseif ($serializationPair[1] instanceof Serializer && $serializationPair[1]->hasInChildren($serializer))
            {
                return true;
            }
        }

        return false;
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

    public function serialize(\Aztech\Events\Event $object)
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
