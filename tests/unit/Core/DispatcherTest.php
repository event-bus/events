<?php

namespace Aztech\Events\Tests\Core;

use Aztech\Events\Core\Dispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testDispatchInvokesRegisteredListeners()
    {
        $event = $this->getMock('\Aztech\Events\Event');
        $subscriber = $this->getMock('\Aztech\Events\Subscriber');

        $subscriber->expects($this->any())
            ->method('supports')
            ->withAnyParameters()
            ->willReturn(true);

        $subscriber->expects($this->once())
            ->method('handle')
            ->with($event);

        $dispatcher = new Dispatcher();
        $dispatcher->addListener('#', $subscriber);

        $dispatcher->dispatch($event);
    }

}
