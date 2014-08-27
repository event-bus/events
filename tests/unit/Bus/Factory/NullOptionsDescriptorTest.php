<?php

namespace Aztech\Events\Tests\Bus\Factory;

use Aztech\Events\Bus\Factory\NullOptionsDescriptor;

class NullOptionsDescriptorTest extends \PHPUnit_Framework_TestCase
{

    public function testGetOptionKeysReturnsEmptyArray()
    {
        $descriptor = new NullOptionsDescriptor();

        $this->assertEmpty($descriptor->getOptionKeys());
    }

    public function testGetOptionDefaultsReturnsEmptyArray()
    {
        $descriptor = new NullOptionsDescriptor();

        $this->assertEmpty($descriptor->getOptionDefaults());
    }

}
