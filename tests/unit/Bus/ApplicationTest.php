<?php

namespace Aztech\Events\Tests\Bus;

use Aztech\Events\EventDispatcher;
use Aztech\Events\Bus\Application;
use Aztech\Events\Bus\Event;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    private $processor;

    private $dispatcher;

    protected function setUp()
    {
        $this->processor = $this->getMock('\Aztech\Events\Bus\Processor');
        $this->dispatcher = new EventDispatcher();
    }

    public function testProcessingIsDeferredToProcessor()
    {
        $this->processor->expects($this->once())
            ->method('processNext')
            ->with($this->dispatcher);

        $application = new Application($this->processor, $this->dispatcher);
        $application->processNext($this->dispatcher);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBindingNonCallableAndNonSubscriberObjectThrowsException()
    {
        $application = new Application($this->processor, $this->dispatcher);

        $application->on('#.anything', new \stdClass());
    }

    public function testBoundCallbackIsInvoked()
    {
        $application = new Application($this->processor, $this->dispatcher);
        $invoked = false;

        $application->on('#', function () use (& $invoked)
        {
            $invoked = true;
        });

        $event = new Event('whatever');

        $this->dispatcher->dispatch($event);

        $this->assertTrue($invoked);
    }
}
