<?php

namespace Aztech\Events\Tests\Bus\Transport;

use Aztech\Events\Bus\Transport\WriteOnlyProvider;

class WriteOnlyProviderTest extends \PHPUnit_Framework_TestCase
{

    private $writer;

    protected function setUp()
    {
        $this->writer = $this->getMock('\Aztech\Events\Bus\Transport\Writer');
    }

    public function testCanReadReturnsFalse()
    {
        $provider = new WriteOnlyProvider($this->writer);

        $this->assertFalse($provider->canRead());
    }

    public function testCanWriteReturnsTrue()
    {
        $provider = new WriteOnlyProvider($this->writer);

        $this->assertTrue($provider->canWrite());
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testGetReaderThrowsException()
    {
        $provider = new WriteOnlyProvider($this->writer);

        $provider->getReader();
    }

    public function testGetWriterReturnsCorrectInstance()
    {
        $provider = new WriteOnlyProvider($this->writer);

        $this->assertSame($this->writer, $provider->getWriter());

    }
}
