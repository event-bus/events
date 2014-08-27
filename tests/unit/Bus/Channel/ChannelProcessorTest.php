<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\ChannelProcessor;

class ChannelProcessorTest extends \PHPUnit_Framework_TestCase
{

    private $processor;

    private $serializer;

    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('\Aztech\Events\Bus\Channel\ChannelReader');
        $this->serializer = $this->getMock('Aztech\Events\Bus\Serializer');

        $this->processor = new ChannelProcessor($this->reader, $this->serializer);
    }

    public function testProcessCorrectlyDispatchesEvent()
    {
        $dispatcher = $this->getMock('\Aztech\Events\Dispatcher');
        $event = $this->getMock('\Aztech\Events\Event');

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->with('data')
            ->willReturn($event);

        $this->reader->expects($this->once())
            ->method('read')
            ->willReturn('data');

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($event));

        $this->processor->processNext($dispatcher);
    }

    public function testProcessDoesNotDispatchNullEvents()
    {
        $dispatcher = $this->getMock('\Aztech\Events\Dispatcher');
        $event = $this->getMock('\Aztech\Events\Event');

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->with('data')
            ->willReturn($event);

        $this->reader->expects($this->once())
            ->method('read')
            ->willReturn(null);

        $dispatcher->expects($this->never())
            ->method('dispatch')
            ->with($this->equalTo($event));

        $this->processor->processNext($dispatcher);
    }

    public function testProcessDoesNotDispatchNullEvents2()
    {
        $dispatcher = $this->getMock('\Aztech\Events\Dispatcher');
        $event = $this->getMock('\Aztech\Events\Event');

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->with('data')
            ->willReturn(null);

        $this->reader->expects($this->once())
            ->method('read')
            ->willReturn('data');

        $dispatcher->expects($this->never())
            ->method('dispatch')
            ->with($this->equalTo($event));

        $this->processor->processNext($dispatcher);
    }
}
