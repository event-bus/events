<?php

namespace Aztech\Events\Tests\Core\Serializer;

use Aztech\Events\Core\Serializer\JsonSerializer;
use Aztech\Events\Core\Event;

class JsonSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function testSerializationPassReturnsIdenticalObject()
    {
        $object = new Event('category');
        $serializer = new JsonSerializer();

        $serializedObject = $serializer->serialize($object);
        $unserializedObject = $serializer->deserialize($serializedObject);

        $this->assertEquals($object, $unserializedObject);

        $object = new Event('category', array('property' => 'value'));
        $serializer = new JsonSerializer();

        $serializedObject = $serializer->serialize($object);
        $unserializedObject = $serializer->deserialize($serializedObject);

        $this->assertEquals($object, $unserializedObject);
    }
}
