<?php

namespace Aztech\Events\Tests\Bus\Transport;

use Aztech\Events\Bus\Transport\NullReader;

class NullReaderTest extends \PHPUnit_Framework_TestCase
{

    public function testReadReturnsNull()
    {
        $reader = new NullReader();

        for ($i = 0; $i < rand(10, 20); $i++) {
            // Ensure it returns null many times (ie always)
            $this->assertNull($reader->read());
        }
    }
}
