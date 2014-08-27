<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\WriteOnlyChannel;

class WriteOnlyChannelTest extends \PHPUnit_Framework_TestCase
{

    private $writer;

    protected function setUp()
    {
        $this->writer = $this->getMock('\Aztech\Events\Bus\Channel\ChannelWriter');
    }

    public function testCanReadReturnsFalse()
    {
        $provider = new WriteOnlyChannel($this->writer);

        $this->assertFalse($provider->canRead());
    }

    public function testCanWriteReturnsTrue()
    {
        $provider = new WriteOnlyChannel($this->writer);

        $this->assertTrue($provider->canWrite());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetChannelReaderThrowsException()
    {
        $provider = new WriteOnlyChannel($this->writer);

        $provider->getReader();
    }

    public function testGetChannelWriterReturnsCorrectInstance()
    {
        $provider = new WriteOnlyChannel($this->writer);

        $this->assertSame($this->writer, $provider->getWriter());

    }
}
