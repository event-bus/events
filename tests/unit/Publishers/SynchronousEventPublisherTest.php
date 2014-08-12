<?php

namespace Evaneos\Events\Tests;

use Evaneos\Events\Publishers\SynchronousEventPublisher;

class SynchronousEventPublisherTest extends \PHPUnit_Framework_TestCase
{

    private $mockDispatcher;

    protected function setUp()
    {
        $this->mockDispatcher = $this->getMock('\Evaneos\Events\EventDispatcher');
    }

    public function testPublishForwardsEventToDispatcherSynchronously()
    {
        $event = $this->getMock('\Evaneos\Events\Event');

        $this->mockDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($event));

        $publisher = new SynchronousEventPublisher($this->mockDispatcher);

        $publisher->publish($event);
    }

}