<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Util\Pattern\PatternMatcher;
use Aztech\Events\Event as EventInterface;

class GenericSerializer implements Serializer
{

    private $serializationMap = array();

    public function bindSerializer($eventFilter, Serializer $serializer)
    {
        if ($serializer === $this || ($serializer instanceof GenericSerializer && $serializer->hasInChildren($this))) {
            throw new \InvalidArgumentException('Cannot bind to self or to serializer containing self, infinite recursion ahead.');
        }

        array_unshift($this->serializationMap, array(
            new PatternMatcher($eventFilter),
            $serializer
        ));
    }

    /**
     * Checks that the given serializer was not already added to the serialization map
     * @param Serializer $serializer
     * @return boolean
     */
    private function hasInChildren(GenericSerializer $serializer)
    {
        foreach ($this->serializationMap as $serializationPair) {
            if ($serializationPair[1] === $serializer) {
                return true;
            }
            elseif ($this->guardedHasInChildren($serializer, $serializationPair[1])) {
                return true;
            }
        }

        return false;
    }

    private function guardedHasInChildren(GenericSerializer $serializer, Serializer $container)
    {
        return ($container instanceof GenericSerializer &&
            $container->hasInChildren($serializer));
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

    public function serialize(EventInterface $object)
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
