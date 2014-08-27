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
}
