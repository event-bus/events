<?php

namespace Aztech\Events\Tests\Bus\Transport;

use Aztech\Events\Bus\Transport\LegacyTransportAdapter;

class LegacyTransportAdapterTest extends \PHPUnit_Framework_TestCase
{

    private $transport;

    protected function setUp()
    {
        $this->transport = $this->getMock('\Aztech\Events\Bus\Transport');
    }

    public function testCanReadReturnsConstructorArgValue()
    {
        $adapter = new LegacyTransportAdapter($this->transport, true, false);
        $this->assertFalse($adapter->canRead());

        $adapter = new LegacyTransportAdapter($this->transport, false, false);
        $this->assertTrue($adapter->canRead());

        $adapter = new LegacyTransportAdapter($this->transport, true, true);
        $this->assertFalse($adapter->canRead());

        $adapter = new LegacyTransportAdapter($this->transport, false, true);
        $this->assertTrue($adapter->canRead());
    }

    public function testCanWriteReturnsConstructorArgValue()
    {
        $adapter = new LegacyTransportAdapter($this->transport, false, true);
        $this->assertFalse($adapter->canWrite());

        $adapter = new LegacyTransportAdapter($this->transport, false, false);
        $this->assertTrue($adapter->canWrite());

        $adapter = new LegacyTransportAdapter($this->transport, true, true);
        $this->assertFalse($adapter->canWrite());

        $adapter = new LegacyTransportAdapter($this->transport, true, false);
        $this->assertTrue($adapter->canWrite());
    }

    public function testGetReaderReturnsTransport()
    {
        $adapter = new LegacyTransportAdapter($this->transport);

        $this->assertSame($this->transport, $adapter->getReader());
    }

    public function testGetWriterReturnsTransport()
    {
        $adapter = new LegacyTransportAdapter($this->transport);

        $this->assertSame($this->transport, $adapter->getWriter());
    }
}
