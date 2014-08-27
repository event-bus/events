<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\NullChannelWriter;
use Aztech\Events\Bus\Event;

class NullChannelWriterTest extends \PHPUnit_Framework_TestCase
{

    public function testReadDoesNothing()
    {
        $writer = new NullChannelWriter();
        $event = new Event('test');
        $serializedData = '';

        $this->assertNull($writer->write($event, $serializedData));
    }
}
