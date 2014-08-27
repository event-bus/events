<?php

namespace Aztech\Events\Tests\Bus\Plugins\Memory;

use Aztech\Events\Bus\Plugins\Memory\MemoryChannelReaderWriter;
use Aztech\Events\Bus\Event;
class MemoryChannelReaderWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testReadReturnsNullWithEmptyEventStack()
    {
        $channel = new MemoryChannelReaderWriter();

        $this->assertNull($channel->read());
    }

    public function testReadReturnsFirstMessageWithNonEmptyEventStack()
    {
        $event = new Event('test');

        $channel = new MemoryChannelReaderWriter();

        for ($i = 1; $i < 10; $i++) {
            $channel->write($event, $i);
        }

        for ($i = 1; $i < 10; $i++) {
            $this->assertEquals($i, $channel->read($event));
        }
    }
}
