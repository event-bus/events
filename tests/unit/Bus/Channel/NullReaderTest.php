<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\NullChannelReader;

class NullChannelReaderTest extends \PHPUnit_Framework_TestCase
{

    public function testReadReturnsNull()
    {
        $reader = new NullChannelReader();

        for ($i = 0; $i < rand(10, 20); $i++) {
            // Ensure it returns null many times (ie always)
            $this->assertNull($reader->read());
        }
    }
}
