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
        $plugin = $this->getMock('\Aztech\Events\Bus\Plugin');
        $plugin->expects($this->any())
            ->method('hasTransport')
            ->willReturn(true);

        $plugin->expects($this->any())
            ->method('hasFactory')
            ->willReturn(true);

        $plugin->expects($this->any())
            ->method('canPublish')
            ->willReturn(true);

        $plugin->expects($this->any())
            ->method('canProcess')
            ->willReturn(true);

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
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAddPluginWithNoTransportOrFactory()
    {
        $name = 'key';
        $plugin = $this->getMock('\Aztech\Events\Bus\Plugin');

        Events::addPlugin($name, $plugin);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAddPluginWithNeitherPublishNorProcessFeature()
    {
        $name = 'key';
        $plugin = $this->getMock('\Aztech\Events\Bus\Plugin');
        $plugin->expects($this->any())
            ->method('hasTransport')
            ->willReturn(true);

        $plugin->expects($this->any())
            ->method('hasFactory')
            ->willReturn(true);

        Events::addPlugin($name, $plugin);
    }
}
