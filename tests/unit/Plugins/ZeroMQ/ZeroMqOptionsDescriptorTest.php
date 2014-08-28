<?php

namespace Aztech\Events\Tests\Bus\Plugins\ZeroMq;

use Aztech\Events\Bus\Plugins\ZeroMq\ZeroMqOptionsDescriptor;

class ZeroMqOptionsDescriptorTest extends \PHPUnit_Framework_TestCase
{

    public function testGetOptionsContainsCorrectKeys()
    {
        $descriptor = new ZeroMqOptionsDescriptor();
        $keys = $descriptor->getOptionKeys();

        $this->assertContains('scheme', $keys);
        $this->assertContains('port', $keys);
        $this->assertContains('publisher', $keys);
        $this->assertContains('subscriber', $keys);
        $this->assertContains('multicast', $keys);
    }

    public function testGetDefaultsContainsCorrectValues()
    {
        $descriptor = new ZeroMqOptionsDescriptor();
        $keys = $descriptor->getOptionDefaults();

        $this->assertArrayHasKey('scheme', $keys);
        $this->assertArrayHasKey('port', $keys);
        $this->assertArrayHasKey('publisher', $keys);
        $this->assertArrayHasKey('subscriber', $keys);
        $this->assertArrayHasKey('multicast', $keys);
    }
}
