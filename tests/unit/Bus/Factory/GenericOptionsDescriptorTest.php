<?php

namespace Aztech\Events\Tests\Bus\Factory;

use Aztech\Events\Bus\Factory\GenericOptionsDescriptor;

class GenericOptionsDescriptorTest extends \PHPUnit_Framework_TestCase
{

    public function testDescriptorInitializesToEmptyOptionKeys()
    {
        $descriptor = new GenericOptionsDescriptor();

        $this->assertEmpty($descriptor->getOptionKeys());
    }

    public function testDescriptorInitializesToEmptyOptionDefaults()
    {
        $descriptor = new GenericOptionsDescriptor();

        $this->assertEmpty($descriptor->getOptionDefaults());
    }

    public function testRequiredOptionAreCorrectlyAdded()
    {
        $descriptor = new GenericOptionsDescriptor();

        $descriptor->addOption('test');

        $this->assertContains('test', $descriptor->getOptionKeys());
        $this->assertArrayNotHasKey('test', $descriptor->getOptionDefaults());
    }

    public function testNonRequiredOptionAreCorrectlyAdded()
    {
        $descriptor = new GenericOptionsDescriptor();

        $descriptor->addOption('test', false, null);

        $this->assertContains('test', $descriptor->getOptionKeys());
        $this->assertArrayHasKey('test', $descriptor->getOptionDefaults());
    }
}
