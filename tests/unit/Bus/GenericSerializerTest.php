<?php

namespace Aztech\Events\Tests\Bus;

use Aztech\Events\Bus\Serializer;
use Aztech\Events\Bus\GenericSerializer;

class GenericSerializerTest extends \PHPUnit_Framework_TestCase
{

    private $event;

    private $serializer;

    private $eventSerializer;

    private $serializationCallback;

    protected function setUp()
    {
        $this->serializationCallback = function () {
            $obj = new \stdClass();
            $obj->category = 'test';

            return json_encode($obj);
        };

        $this->event = $this->getMock('\Aztech\Events\Event');
        $this->event->expects($this->any())
            ->method('getCategory')
            ->will($this->returnValue('test'));

        $this->serializer = $this->getMock('Aztech\Events\Bus\Serializer');

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnCallback($this->serializationCallback));

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->will($this->returnValue($this->event));

        $this->eventSerializer = new GenericSerializer();
        $this->eventSerializer->bindSerializer('test', $this->serializer);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingToSelfThrowsExceptionToPreventRecursion()
    {
        $serializer = new GenericSerializer();
        $serializer->bindSerializer('#', $serializer);
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingToSelfViaNestingThrowsExceptionToPreventRecursion()
    {
        $serializer = new GenericSerializer();
        $nested = new GenericSerializer();

        $nested->bindSerializer('#', $serializer);
        $serializer->bindSerializer('#', $nested);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingToSelfViaDeeperNestingThrowsExceptionToPreventRecursion()
    {
        $serializer = new GenericSerializer();
        $nested = new GenericSerializer();
        $deepest = new GenericSerializer();

        $deepest->bindSerializer('#', $serializer);
        $nested->bindSerializer('#', $deepest);
        $serializer->bindSerializer('#', $nested);
    }


    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetUnboundCategorySerializerThrowsException()
    {
        $serializer = new GenericSerializer();

        $serializer->getSerializer('test');
    }

    public function testGetBoundCategorySerializerReturnsCorrectSerializer()
    {
        $serializer = new GenericSerializer();
        $serializer->bindSerializer('test', $this->serializer);

        $this->assertSame($this->serializer, $serializer->getSerializer('test'));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testSerializingUnboundCategoryThrowsException()
    {
        $serializer = new GenericSerializer();

        $serializer->serialize($this->event);
    }


    /**
     * @expectedException \OutOfBoundsException
     */
    public function testDeserializingUnboundCategoryThrowsException()
    {
        $serializer = new GenericSerializer();

        $serializer->deserialize($this->serializer->serialize($this->event));
    }

    public function testSerializeReturnsExpectedValue()
    {
        $actual = $this->eventSerializer->serialize($this->event);
        $serializationCallback = $this->serializationCallback;

        $this->assertEquals($serializationCallback(), $actual);
    }


    public function testDeserializeReturnsExpectedValue()
    {
        $serializationCallback = $this->serializationCallback;
        $actual = $this->eventSerializer->deserialize($serializationCallback());

        $this->assertEquals($this->event, $actual);
    }

    public function testDeserializeReturnsNullOnIncorrectSerializedValues()
    {
        $serializedObject = 'randome invalid data';
        $actual = $this->eventSerializer->deserialize($serializedObject);

        $this->assertNull($actual);
    }
}
