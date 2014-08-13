<?php

namespace Aztech\Events\Tests\Core;

use Aztech\Events\Core\NullDispatcher;
class NullDispatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldNotInvokeSubscriberOnDispatcher()
    {
        $subscriber = $this->getMock('\Aztech\Events\Subscriber');
        $subscriber->expects($this->never())
            ->method('handle');
        $subscriber->expects($this->never())
            ->method('supports');

        $event = $this->getMock('\Aztech\Events\Event');

        $dispatcher = new NullDispatcher();
        $dispatcher->addListener(' "', $subscriber);

        $dispatcher->dispatch($event);
    }

}
