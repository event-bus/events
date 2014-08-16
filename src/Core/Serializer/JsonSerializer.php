<?php

namespace Aztech\Events\Core\Serializer;

use Aztech\Events\Core\AbstractEvent;
use Aztech\Events\Event;
use Instantiator\Instantiator;

class JsonSerializer implements \Aztech\Events\Serializer
{

    private $instantiator;

    public function __construct()
    {
        $this->instantiator = new Instantiator();
    }

    public function serialize(\Aztech\Events\Event $object)
    {
        $properties=  $this->getProperties($object);
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

    private function getProperties($object)
    {
        if ($object instanceof AbstractEvent) {
            $properties = $object->getProperties();
        }
        else {
            $properties = $this->getPropertiesViaReflection($object);
        }

        return $properties;
    }

    private function getPropertiesViaReflection($object)
    {
        $reflectionObject = new \ReflectionClass(get_class($object));
        $reflectionProperties = $this->getSerializableReflectionProperties($object, $reflectionObject);
        $properties = array();

        foreach ($reflectionProperties as $reflectionProperty) {
            $this->ensurePropertyIsAccessible($reflectionProperty);
            $properties[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);
            $this->restorePropertyAccessibility($reflectionProperty);
        }

        return $properties;
    }

    private function getSerializableReflectionProperties($object, $reflectionObject)
    {
        if (method_exists($object, '__sleep')) {
            $reflectionProperties = array_map(function ($name) use ($reflectionObject) {
                return $reflectionObject->getProperty($name);
            }, $object->__sleep());
        }
        else {
            $reflectionProperties = $reflectionObject->getProperties(\ReflectionProperty::IS_PUBLIC |
                \ReflectionProperty::IS_PROTECTED |
                \ReflectionProperty::IS_PRIVATE);
        }

        return $reflectionProperties;
    }

    private function ensurePropertyIsAccessible(\ReflectionProperty $property)
    {
        if ($property->isPrivate() || $property->isPrivate()) {
            $property->setAccessible(true);
        }
    }

    private function restorePropertyAccessibility(\ReflectionProperty $property)
    {
        if ($property->isPrivate() || $property->isProtected()) {
            $property->setAccessible(false);
        }
    }

    public function deserialize($serializedObject)
    {
        $dataObj = json_decode($serializedObject, true);

        $class = $dataObj['class'];
        $properties = $dataObj['properties'];

        if (empty($class) || ! class_exists($class)) {
            return null;
        }

        $object = $this->instantiator->instantiate($class);

        $this->setProperties($object, $properties);
        $this->restoreState($object);

        return $object;
    }

    private function setProperties($object, $properties)
    {
        if ($object instanceof AbstractEvent) {
            $object->setProperties($properties);
        }
        else {
            $this->reflectionSetProperties($object, $properties);
        }
    }

    private function reflectionSetProperties($object, $properties)
    {
        $reflectionObject = new \ReflectionClass(get_class($object));
        $reflectionProperties = $this->getSerializableReflectionProperties($object, $reflectionObject);

        foreach ($reflectionProperties as $reflectionProperty) {
            $this->ensurePropertyIsAccessible($reflectionProperty);
            $reflectionProperty->setValue($object, $properties[$reflectionProperty->getName()]);
            $this->restorePropertyAccessibility($reflectionProperty);
        }
    }

    private function restoreState($object)
    {
        if (method_exists($object, '__wakeup')) {
            $object->__wakeup();
        }
    }
}
