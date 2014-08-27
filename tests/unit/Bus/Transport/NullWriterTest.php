<?php

namespace Aztech\Events\Tests\Bus\Transport;

use Aztech\Events\Bus\Transport\NullWriter;
use Aztech\Events\Bus\Event;

class NullWriterTest extends \PHPUnit_Framework_TestCase
{

    public function testReadDoesNothing()
    {
        $writer = new NullWriter();
        $event = new Event('test');
        $serializedData = '';

        $this->assertNull($writer->write($event, $serializedData));
    }
}
