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

    public function testSerializationPassReturnsIdenticalObjectWithNonAbstractEvents()
    {
        $object = $this->getMock('\Aztech\Events\Event');
        $serializer = new JsonSerializer();

        $serializedObject = $serializer->serialize($object);
        $unserializedObject = $serializer->deserialize($serializedObject);

        $this->assertEquals($object, $unserializedObject);
    }

    public function testSerializationPassReturnsObjectWithCorrectPropertiesForSleepableEvents()
    {
        $object = new JsonSleepable();
        $serializer = new JsonSerializer();

        $object->notRestorable = 'bla';
        $object->other = true;
        $object->restorable = 'restore-me';

        $serializedObject = $serializer->serialize($object);
        $unserializedObject = $serializer->deserialize($serializedObject);

        $this->assertEquals($object->other, $unserializedObject->other);
        $this->assertEquals($object->restorable, $unserializedObject->restorable);
        $this->assertFalse($object->wakeupInvoked);
        $this->assertTrue($unserializedObject->wakeupInvoked);
        $this->assertEquals(null, $unserializedObject->notRestorable);
    }
}

class JsonSleepable implements \Aztech\Events\Event
{

    public $notRestorable = null;

    public $restorable = '';

    public $other = '';

    public $wakeupInvoked = false;

    public function getId()
    {
        return 1;
    }

    public function getCategory()
    {
        return 'category';
    }

    public function __sleep()
    {
        $this->wakeupInvoked = false;
        return array('restorable', 'other');
    }

    public function __wakeup()
    {
        $this->wakeupInvoked = true;
    }

}
