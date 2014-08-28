<?php

namespace Aztech\Events\Tests\Bus;

use Aztech\Events\Bus\Events;

class EventsTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        Events::reset();
    }

    protected function generatePlugin()
    {
        $plugin = $this->getMock('\Aztech\Events\Bus\PluginFactory');

        return $plugin;
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAddPluginWithEmptyName()
    {
        $name = '';
        $plugin = $this->generatePlugin();

        Events::addPlugin($name, $plugin);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAddPluginWithDuplicateKey()
    {
        $name = 'key';
        $first = $this->generatePlugin();
        $second = $this->generatePlugin();

        Events::addPlugin($name, $first);
        Events::addPlugin($name, $second);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAddTheSamePluginWithDifferentKeys()
    {
        $name = 'key';
        $nextName = 'new-key';
        $plugin = $this->generatePlugin();

        Events::addPlugin($name, $plugin);
        Events::addPlugin($nextName, $plugin);
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetPluginThrowsExceptionForUnregisteredKeys()
    {
        Events::getPlugin('test');
    }

    public function testGetPluginReturnsRegisteredInstance()
    {
        $plugin = $this->getMock('\Aztech\Events\Bus\PluginFactory');

        Events::addPlugin('test', $plugin);

        $this->assertSame($plugin, Events::getPlugin('test'));
    }

    public function testCreateReturnsEvent()
    {
        $event = Events::create('test', array('property' => 'value'));

        $this->assertNotNull($event);
        $this->assertInstanceOf('\Aztech\Events\Event', $event);
        $this->assertEquals('test', $event->getCategory());
    }
}
