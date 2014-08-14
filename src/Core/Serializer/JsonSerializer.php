<?php

namespace Aztech\Events\Core\Serializer;

use Aztech\Events\Core\AbstractEvent;
use Aztech\Events\Event;

class JsonSerializer implements \Aztech\Events\Serializer
{

    public function serialize(\Aztech\Events\Event $object)
    {
        if (! ($object instanceof Event)) {
            throw new \InvalidArgumentException();
        }

        if ($object instanceof AbstractEvent) {
            $properties = $object->getProperties();
        }
        else {
            $properties = $this->reflectProperties($object);
        }

        $class = get_class($object);

        $dataObj = new \stdClass();

        $dataObj->class = $class;
        $dataObj->properties = $properties;
        $dataObj->category = $object->getCategory();

        // PHP 5.3 compatibility
        $unescapedSlashes = defined('JSON_UNESCAPED_SLASHES') ? JSON_UNESCAPED_SLASHES : 64;
        $unescapedUnicode = defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 256;

        return json_encode($dataObj, $unescapedSlashes | $unescapedUnicode);
    }

    private function reflectProperties($object)
    {
        $reflectionObject = new \ReflectionClass(get_class($object));
        $properties = array();

        if (method_exists($object, '__sleep')) {
            $reflectionProperties = $object->__sleep();
        }
        else {
            $reflectionProperties = $reflectionObject->getProperties(ReflectionProperty::IS_PUBLIC |
                ReflectionProperty::IS_PROTECTED |
                ReflectionProperty::IS_PRIVATE);
        }

        foreach ($reflectionProperties as $reflectionProperty) {
            if (! ($reflectionProperty instanceof \ReflectionProperty)) {
                $reflectionProperty = $reflectionObject->getProperty($reflectionProperty);
            }

            $properties[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);
        }

        return $properties;
    }

    public function deserialize($serializedObject)
    {
        $dataObj = json_decode($serializedObject, true);

        $class = $dataObj['class'];
        $properties = $dataObj['properties'];

        $reflectionClass = new \ReflectionClass($class);
        $obj = $reflectionClass->newInstanceWithoutConstructor();

        if ($obj instanceof AbstractEvent) {
            $obj->setProperties($properties);
        }
        else {
            $this->injectProperties($obj, $properties);
        }

        if (method_exists($obj, '__wakeup')) {
            $obj->__wakeup();
        }

        return $obj;
    }

    private function injectProperties($object, $properties)
    {
        $reflectionObject = new \ReflectionClass(get_class($object));
        $reflectionProperties = $reflectionObject->getProperties(ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE);
        $properties = array();

        foreach ($reflectionProperties as $reflectionProperty) {
            /* @var $reflectionProperty \ReflectionProperty */
            $reflectionProperty->setValue($object, $properties[$reflectionProperty->getName()]);
        }

    }
}
