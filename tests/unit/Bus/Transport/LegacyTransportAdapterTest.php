<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\LegacyChannelAdapter;

class LegacyChannelAdapterTest extends \PHPUnit_Framework_TestCase
{

    private $transport;

    protected function setUp()
    {
        $this->transport = $this->getMock('\Aztech\Events\Bus\Channel');
    }

    public function testCanReadReturnsConstructorArgValue()
    {
        $adapter = new LegacyChannelAdapter($this->transport, true, false);
        $this->assertFalse($adapter->canRead());

        $adapter = new LegacyChannelAdapter($this->transport, false, false);
        $this->assertTrue($adapter->canRead());

        $adapter = new LegacyChannelAdapter($this->transport, true, true);
        $this->assertFalse($adapter->canRead());

        $adapter = new LegacyChannelAdapter($this->transport, false, true);
        $this->assertTrue($adapter->canRead());
    }

    public function testCanWriteReturnsConstructorArgValue()
    {
        $adapter = new LegacyChannelAdapter($this->transport, false, true);
        $this->assertFalse($adapter->canWrite());

        $adapter = new LegacyChannelAdapter($this->transport, false, false);
        $this->assertTrue($adapter->canWrite());

        $adapter = new LegacyChannelAdapter($this->transport, true, true);
        $this->assertFalse($adapter->canWrite());

        $adapter = new LegacyChannelAdapter($this->transport, true, false);
        $this->assertTrue($adapter->canWrite());
    }

    public function testGetChannelReaderReturnsChannel()
    {
        $adapter = new LegacyChannelAdapter($this->transport);

        $this->assertSame($this->transport, $adapter->getReader());
    }

    public function testGetChannelWriterReturnsChannel()
    {
        $adapter = new LegacyChannelAdapter($this->transport);

        $this->assertSame($this->transport, $adapter->getWriter());
    }
}
