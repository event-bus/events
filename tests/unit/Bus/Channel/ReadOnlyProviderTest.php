<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\ReadOnlyChannel;

class ReadOnlyChannelTest extends \PHPUnit_Framework_TestCase
{

    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('\Aztech\Events\Bus\Channel\ChannelReader');
    }

    public function testCanReadReturnsTrue()
    {
        $provider = new ReadOnlyChannel($this->reader);

        $this->assertTrue($provider->canRead());
    }

    public function testCanWriteReturnsFalse()
    {
        $provider = new ReadOnlyChannel($this->reader);

        $this->assertFalse($provider->canWrite());
    }

    public function testGetChannelReaderReturnsCorrectInstance()
    {
        $provider = new ReadOnlyChannel($this->reader);

        $this->assertSame($this->reader, $provider->getReader());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetChannelWriterThrowsException()
    {
        $provider = new ReadOnlyChannel($this->reader);

        $provider->getWriter();
    }
}
