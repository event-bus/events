<?php

namespace Aztech\Events\Tests\Bus;

use Aztech\Events\Bus\GenericPlugin;

class GenericPluginTest extends \PHPUnit_Framework_TestCase
{

    public function testSetProcessFlagCorrectlyChangesPluginFeatureDescription()
    {
        $plugin = new GenericPlugin();

        $plugin->setProcessFlag(false);
        $this->assertFalse($plugin->canProcess());

        $plugin->setProcessFlag(true);
        $this->assertTrue($plugin->canProcess());
    }

    public function testSetPublishFlagCorrectlyChangesPluginFeatureDescription()
    {
        $plugin = new GenericPlugin();

        $plugin->setPublishFlag(false);
        $this->assertFalse($plugin->canPublish());

        $plugin->setPublishFlag(true);
        $this->assertTrue($plugin->canPublish());
    }

    public function testSetChannelCorrectlyChangesPluginFeatureDescription()
    {
        $plugin = new GenericPlugin();

        $transport = $this->getMock('\Aztech\Events\Bus\Channel');

        $this->assertFalse($plugin->hasChannel());

        $plugin->setChannel($transport);

        $this->assertTrue($plugin->hasChannel());
        $this->assertSame($transport, $plugin->getChannel());
    }


    public function testSetFactoryCorrectlyChangesPluginFeatureDescription()
    {
        $plugin = new GenericPlugin();

        $factory = $this->getMockBuilder('\Aztech\Events\Bus\Factory')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->assertFalse($plugin->hasFactory());

        $plugin->setFactory($factory);

        $this->assertTrue($plugin->hasFactory());
        $this->assertSame($factory, $plugin->getFactory());
    }
}

