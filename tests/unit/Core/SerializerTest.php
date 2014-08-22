<?php

namespace Aztech\Events\Tests\Bus;

use Aztech\Events\Bus\Serializer;

class SerializerTest extends \PHPUnit_Framework_TestCase
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

        $this->serializer = $this->getMock('\Aztech\Events\Serializer');

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnCallback($this->serializationCallback));

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->will($this->returnValue($this->event));

        $this->eventSerializer = new Serializer();
        $this->eventSerializer->bindSerializer('test', $this->serializer);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingToSelfThrowsExceptionToPreventRecursion()
    {
        $serializer = new Serializer();
        $serializer->bindSerializer('#', $serializer);
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingToSelfViaNestingThrowsExceptionToPreventRecursion()
    {
        $serializer = new Serializer();
        $nested = new Serializer();

        $nested->bindSerializer('#', $serializer);
        $serializer->bindSerializer('#', $nested);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddingToSelfViaDeeperNestingThrowsExceptionToPreventRecursion()
    {
        $serializer = new Serializer();
        $nested = new Serializer();
        $deepest = new Serializer();

        $deepest->bindSerializer('#', $serializer);
        $nested->bindSerializer('#', $deepest);
        $serializer->bindSerializer('#', $nested);
    }


    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetUnboundCategorySerializerThrowsException()
    {
        $serializer = new Serializer();

        $serializer->getSerializer('test');
    }

    public function testGetBoundCategorySerializerReturnsCorrectSerializer()
    {
        $serializer = new Serializer();
        $serializer->bindSerializer('test', $this->serializer);

        $this->assertSame($this->serializer, $serializer->getSerializer('test'));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testSerializingUnboundCategoryThrowsException()
    {
        $serializer = new Serializer();

        $serializer->serialize($this->event);
    }


    /**
     * @expectedException \OutOfBoundsException
     */
    public function testDeserializingUnboundCategoryThrowsException()
    {
        $serializer = new Serializer();

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
