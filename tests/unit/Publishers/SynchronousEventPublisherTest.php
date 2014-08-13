<?php

namespace Aztech\Events\Tests;

use Aztech\Events\Publishers\SynchronousEventPublisher;
use Aztech\Events\Core\Publisher\SynchronousPublisher;

class SynchronousEventPublisherTest extends \PHPUnit_Framework_TestCase
{

    private $mockDispatcher;

    protected function setUp()
    {
        $this->mockDispatcher = $this->getMock('\Aztech\Events\Dispatcher');
    }

    public function testPublishForwardsEventToDispatcherSynchronously()
    {
        $event = $this->getMock('\Aztech\Events\Event');

        $this->mockDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo($event));

        $publisher = new SynchronousPublisher($this->mockDispatcher);

        $publisher->publish($event);
    }

}
