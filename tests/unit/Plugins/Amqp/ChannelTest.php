<?php

namespace Aztech\Events\Tests\Publishers\Amqp;

use Aztech\Events\Bus\Plugins\Amqp\Channel;

class ChannelTest extends \PHPUnit_Framework_TestCase
{

    protected $serializer;

    protected $channel;

    protected function setUp()
    {
        $this->channel = $this->getMockBuilder('\PhpAmqpLib\Channel\AMQPChannel')
            ->disableOriginalConstructor()
            ->getMock();

    }

    public function testEventIsWrittenToQueue()
    {
        $event = $this->getMock('\Aztech\Events\Event');
        $event->expects($this->atLeastOnce())
            ->method('getCategory')
            ->will($this->returnValue('event.category'));

        $this->channel->expects($this->once())
            ->method('basic_publish')
            ->with($this->anything(), $this->equalTo('exchange-name'), $this->equalTo('event.category'));

        $transport = new Channel($this->channel, 'exchange-name', 'event-queue');
        $transport->write($event, 'serialized-data');
    }
}
