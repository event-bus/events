<?php

namespace Aztech\Events\Tests\Bus\Transport;

use Aztech\Events\Bus\Transport\WriteOnlyProvider;
use Aztech\Events\Bus\Transport\ReadWriteProvider;

class ReadWriteProviderTest extends \PHPUnit_Framework_TestCase
{

    private $reader;

    private $writer;

    protected function setUp()
    {
        $this->reader = $this->getMock('\Aztech\Events\Bus\Transport\Reader');
        $this->writer = $this->getMock('\Aztech\Events\Bus\Transport\Writer');
    }

    public function testCanReadReturnsTrue()
    {
        $provider = new ReadWriteProvider($this->reader, $this->writer);

        $this->assertTrue($provider->canRead());
    }

    public function testCanWriteReturnsTrue()
    {
        $provider = new ReadWriteProvider($this->reader, $this->writer);

        $this->assertTrue($provider->canWrite());
    }

    public function testGetReaderReturnsCorrectInstance()
    {
        $provider = new ReadWriteProvider($this->reader, $this->writer);

        $this->assertSame($this->reader, $provider->getReader());

    }

    public function testGetWriterReturnsCorrectInstance()
    {
        $provider = new ReadWriteProvider($this->reader, $this->writer);

        $this->assertSame($this->writer, $provider->getWriter());

    }
}
