<?php

namespace Aztech\Events\Tests\Core\Publisher;

use Aztech\Events\Core\Publisher\TransportPublisher;

class TransportPublisherTest extends \PHPUnit_Framework_TestCase
{

    private $publisher;

    private $serializer;

    private $transport;

    protected function setUp()
    {
        $this->transport = $this->getMock('\Aztech\Events\Transport');
        $this->serializer = $this->getMock('\Aztech\Events\Serializer');

        $this->publisher = new TransportPublisher($this->transport, $this->serializer);
    }

    public function testPublishCallsTransportWithSerializedRepresentationAndEvent()
    {
        $event = $this->getMock('\Aztech\Events\Event');

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->with($event)
            ->willReturn('data');

        $this->transport->expects($this->once())
            ->method('write')
            ->with($this->equalTo($event), $this->equalTo('data'));

        $this->publisher->publish($event);
    }
}
