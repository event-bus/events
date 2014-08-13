<?php

namespace Aztech\Events\Tests\Publishers\RabbitMQ;

use Aztech\Events\Publishers\RabbitMQ\RabbitMQEventPublisher;
use Aztech\Events\Plugins\Amqp\Transport;
use Aztech\Events\Core\Publisher\TransportPublisher;
class RabbitMQEventPublisherTest extends \PHPUnit_Framework_TestCase
{

    protected $serializer;

    protected $channel;

    protected function setUp()
    {
        $this->serializer = $this->getMockBuilder('\Aztech\Events\Serializer')
            ->disableOriginalConstructor()
            ->getMock();

        $this->channel =$this->getMockBuilder('\PhpAmqpLib\Channel\AMQPChannel')
            ->disableOriginalConstructor()
            ->getMock();

    }

    public function testEventIsPublishedToQueue()
    {
        $event = $this->getMock('\Aztech\Events\Event');
        $event->expects($this->atLeastOnce())
            ->method('getCategory')
            ->will($this->returnValue('event.category'));

        $this->channel->expects($this->once())
            ->method('basic_publish')
            ->with($this->anything(), $this->equalTo('exchange-name'), $this->equalTo('event.category'));

        $this->serializer->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue('serialized-data'));

        $transport = new Transport($this->channel, 'exchange-name', 'event-queue');
        $publisher = new TransportPublisher($transport, $this->serializer);

        $publisher->publish($event);
    }
}
