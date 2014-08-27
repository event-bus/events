<?php

namespace Aztech\Events\Tests\Bus\Publisher;

use Aztech\Events\Bus\Publisher\ChannelPublisher;

class ChannelPublisherTest extends \PHPUnit_Framework_TestCase
{

    private $publisher;

    private $serializer;

    private $transport;

    protected function setUp()
    {
        $this->transport = $this->getMock('\Aztech\Events\Bus\Channel');
        $this->serializer = $this->getMock('Aztech\Events\Bus\Serializer');

        $this->publisher = new ChannelPublisher($this->transport, $this->serializer);
    }

    public function testPublishCallsChannelWithSerializedRepresentationAndEvent()
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
