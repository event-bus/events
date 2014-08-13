<?php

namespace Aztech\Events\Core\Serializer;

use Aztech\Events\Core\Event;

class JsonSerializer implements Serializer
{

    public function serialize(\Aztech\Events\Event $object)
    {
        if (! ($object instanceof Event)) {
            throw new \InvalidArgumentException();
        }

        $properties = $object->getProperties();
        $class = get_class($object);

        $dataObj = new \stdClass();

        $dataObj->class = $class;
        $dataObj->properties = $properties;
        $dataObj->category = $object->getCategory();

        return json_encode($dataObj, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
