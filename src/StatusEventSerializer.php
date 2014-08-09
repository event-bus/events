<?php

namespace Evaneos\Events;

class StatusEventSerializer implements Serializer
{

    public function serialize($object)
    {
        if (! ($object instanceof StatusEvent)) {
            throw new \InvalidArgumentException();
        }

        $properties = $object->getProperties();
        $class = get_class($object);

        $dataObj = new \stdClass();
        $dataObj->class = $class;
        $dataObj->properties = $properties;
        $dataObj->category = $object->getCategory();

        return json_encode($dataObj);
    }

    public function deserialize($serializedObject)
    {
        $dataObj = json_decode($serializedObject, true);
        $class = $dataObj['class'];
        $properties = $dataObj['properties'];

        $reflectionClass = new \ReflectionClass($class);
        $obj = $reflectionClass->newInstanceWithoutConstructor();

        if (! ($obj instanceof BaseEvent)) {
            throw new \InvalidArgumentException();
        }

        $obj->setProperties($properties);

        return $obj;
    }
}
