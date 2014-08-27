<?php

namespace Aztech\Events\Tests\Bus;

use Aztech\Events\Bus\Event;
class EventTest extends \PHPUnit_Framework_TestCase
{

    public function testDynamicPropertiesAreCorrectlySetAndReturned()
    {
        $event = new Event('test');

        $event->dummyProperty = 'value';

        $this->assertEquals('value', $event->dummyProperty);
    }

    public function testUnassignedPropertiesAreReturnedAsNull()
    {
        $event = new Event('test');

        $this->assertNull($event->unassignedProperty);
    }

    public function testCallingGetXReturnsAssignedPropertyValue()
    {
        $event = new Event('test');

        $event->dummyProperty ='value';

        $this->assertEquals('value', $event->getDummyProperty());
    }

    public function testCallingGetXReturnsNullForUnassignedProperties()
    {
        $event = new Event('test');

        $this->assertEquals(null, $event->getDummyProperty());
    }

    public function testCallingUnknownMethodReturnsNull()
    {
        $event = new Event('test');

        $this->assertNull($event->doSomething());
    }
}
