<?php

namespace Aztech\Events\Tests\Bus\Processor;

use Aztech\Events\Bus\Processor\TransportProcessor;

class TransportProcessorTest extends \PHPUnit_Framework_TestCase
{

    private $processor;

    private $serializer;

    private $transport;

    protected function setUp()
    {
        $this->transport = $this->getMock('\Aztech\Events\Bus\Transport\Reader');
        $this->serializer = $this->getMock('\Aztech\Events\Bus\Serializer');

        $this->processor = new TransportProcessor($this->transport, $this->serializer);
    }

    public function testProcessNextDispatchesEvent()
    {
        $dispatcher = $this->getMock('\Aztech\Events\Dispatcher');
        $event = $this->getMock('\Aztech\Events\Event');

        $this->transport->expects($this->any())
            ->method('read')
            ->willReturn('data');

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->with('data')
            ->willReturn($event);

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($event));

        $this->processor->processNext($dispatcher);
    }

    public function testProcessNextDoesNotDispatchWhenEventDataIsNotDeserializable()
    {
        $dispatcher = $this->getMock('\Aztech\Events\Dispatcher');

        $this->serializer->expects($this->any())
            ->method('deserialize')
            ->willReturn(null);

        $dispatcher->expects($this->never())
            ->method('dispatch');

        $this->processor->processNext($dispatcher);
    }
}
