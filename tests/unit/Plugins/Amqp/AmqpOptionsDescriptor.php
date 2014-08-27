<?php

namespace Aztech\Events\Tests\Bus\Plugins\Amqp;

use Aztech\Events\Bus\Plugins\Amqp\AmqpOptionsDescriptor;

class AmqpOptionsDescriptorTest extends \PHPUnit_Framework_TestCase
{

    public function testGetOptionsContainsCorrectKeys()
    {
        $descriptor = new AmqpOptionsDescriptor();
        $keys = $descriptor->getOptionKeys();

        $this->assertContains('host', $keys);
        $this->assertContains('port', $keys);
        $this->assertContains('user', $keys);
        $this->assertContains('pass', $keys);
        $this->assertContains('vhost', $keys);
        $this->assertContains('exchange', $keys);
        $this->assertContains('event-queue', $keys);
        $this->assertContains('event-prefix', $keys);
    }

    public function testGetDefaultsContainsCorrectValues()
    {
        $descriptor = new AmqpOptionsDescriptor();
        $keys = $descriptor->getOptionDefaults();

        $this->assertArrayHasKey('host', $keys);
        $this->assertArrayHasKey('port', $keys);
        $this->assertArrayHasKey('user', $keys);
        $this->assertArrayHasKey('pass', $keys);
        $this->assertArrayHasKey('vhost', $keys);
        $this->assertArrayHasKey('exchange', $keys);
        $this->assertArrayHasKey('event-queue', $keys);
        $this->assertArrayHasKey('event-prefix', $keys);
    }
}
