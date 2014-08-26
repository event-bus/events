<?php

namespace Aztech\Events\Tests\Bus\Serializer;

use Aztech\Events\Bus\Serializer\NativeSerializer;
use Aztech\Events\Bus\Event;

class NativeSerializerTest extends \PHPUnit_Framework_TestCase
{

    public function testSerializationPassReturnsCorrectObject()
    {
        $event = new Event('category', array(
            'property' => 'value'
        ));

        $serializer = new NativeSerializer();

        $serializedData = $serializer->serialize($event);
        $unserializedObject = $serializer->deserialize($serializedData);

        $this->assertEquals($event, $unserializedObject);
    }
}
