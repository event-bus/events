<?php

namespace Aztech\Events\Tests\Transport;

use Aztech\Events\Bus\Transport\SocketTransport;
class SocketTransportTest extends \PHPUnit_Framework_TestCase
{

    private $wrapper;

    protected function setUp()
    {
        $this->wrapper = $this->getMockBuilder('\Aztech\Events\Bus\Transport\Socket\Wrapper')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testReadReturnsSocketData()
    {
        $this->wrapper->expects($this->atLeastOnce())
            ->method('readRaw')
            ->willReturn('data');

        $transport = new SocketTransport($this->wrapper);

        $this->assertEquals('data', $transport->read());
    }

    public function testWriteForwardsToSocket()
    {
        $data = 'some-data';

        $this->wrapper->expects($this->atLeastOnce())
            ->method('writeRaw')
            ->with($this->equalTo($data))
            ->willReturn(strlen($data));

        $transport = new SocketTransport($this->wrapper);

        $transport->write($this->getMock('\Aztech\Events\Event'), $data);
    }
}
