<?php

namespace Aztech\Events\Tests\Bus\Channel;

use Aztech\Events\Bus\Channel\ChannelPublisher;

class ChannelPublisherTest extends \PHPUnit_Framework_TestCase
{

    private $publisher;

    private $serializer;

    private $writer;

    protected function setUp()
    {
        $this->writer = $this->getMock('\Aztech\Events\Bus\Channel\ChannelWriter');
        $this->serializer = $this->getMock('Aztech\Events\Bus\Serializer');

        $this->publisher = new ChannelPublisher($this->writer, $this->serializer);
    }

    public function testPublishCallsChannelWithSerializedRepresentationAndEvent()
    {
        $event = $this->getMock('\Aztech\Events\Event');

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->with($event)
            ->willReturn('data');

        $this->writer->expects($this->once())
            ->method('write')
            ->with($this->equalTo($event), $this->equalTo('data'));

        $this->publisher->publish($event);
    }
}
