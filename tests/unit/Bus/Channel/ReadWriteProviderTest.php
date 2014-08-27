<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\WriteOnlyChannel;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class ReadWriteChannelTest extends \PHPUnit_Framework_TestCase
{

    private $reader;

    private $writer;

    protected function setUp()
    {
        $this->reader = $this->getMock('\Aztech\Events\Bus\Channel\ChannelReader');
        $this->writer = $this->getMock('\Aztech\Events\Bus\Channel\ChannelWriter');
    }

    public function testCanReadReturnsTrue()
    {
        $provider = new ReadWriteChannel($this->reader, $this->writer);

        $this->assertTrue($provider->canRead());
    }

    public function testCanWriteReturnsTrue()
    {
        $provider = new ReadWriteChannel($this->reader, $this->writer);

        $this->assertTrue($provider->canWrite());
    }

    public function testGetChannelReaderReturnsCorrectInstance()
    {
        $provider = new ReadWriteChannel($this->reader, $this->writer);

        $this->assertSame($this->reader, $provider->getReader());

    }

    public function testGetChannelWriterReturnsCorrectInstance()
    {
        $provider = new ReadWriteChannel($this->reader, $this->writer);

        $this->assertSame($this->writer, $provider->getWriter());

    }
}
