<?php

namespace Aztech\Events\Tests\Bus\Transport;

use Aztech\Events\Bus\Transport\ReadOnlyProvider;

class ReadOnlyProviderTest extends \PHPUnit_Framework_TestCase
{

    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('\Aztech\Events\Bus\Transport\Reader');
    }

    public function testCanReadReturnsTrue()
    {
        $provider = new ReadOnlyProvider($this->reader);

        $this->assertTrue($provider->canRead());
    }

    public function testCanWriteReturnsFalse()
    {
        $provider = new ReadOnlyProvider($this->reader);

        $this->assertFalse($provider->canWrite());
    }

    public function testGetReaderReturnsCorrectInstance()
    {
        $provider = new ReadOnlyProvider($this->reader);

        $this->assertSame($this->reader, $provider->getReader());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetWriterThrowsException()
    {
        $provider = new ReadOnlyProvider($this->reader);

        $provider->getWriter();
    }
}
