<?php

namespace Aztech\Events\Tests\Bus\Publisher;

use Aztech\Events\Bus\Publisher\RoundRobinPublisher;
use Aztech\Events\Bus\Event;
class RoundRobinPublisherTest extends \PHPUnit_Framework_TestCase
{

    private function getMockPublisher()
    {
        return $this->getMock('\Aztech\Events\Bus\Publisher');
    }

    public function testDispatcherDispatchesToOnePublisherAtATime()
    {
        $publishers = array($this->getMockPublisher(), $this->getMockPublisher());
        $events = array(new Event('test'), new Event('second'));
        $roundrobin = new RoundRobinPublisher();


        $i = 0;

        foreach ($publishers as $publisher) {
            $publisher->expects($this->at(0))
                ->method('publish')
                ->with($events[$i]);

            $roundrobin->addPublisher($publisher);
            $i++;
        }

        foreach ($events as $event) {
            $roundrobin->publish($event);
        }
    }

    public function testRemovedPublishersAreNotInvoked()
    {
        $publishers = array($this->getMockPublisher(), $this->getMockPublisher());
        $events = array(new Event('test'), new Event('second'));
        $roundrobin = new RoundRobinPublisher();

        $publishers[0]->expects($this->never())
            ->method('publish');

        $publishers[1]->expects($this->exactly(count($events)))
            ->method('publish');

        $roundrobin->addPublisher($publishers[0]);
        $roundrobin->addPublisher($publishers[1]);
        $roundrobin->removePublisher($publishers[0]);

        foreach ($events as $event) {
            $roundrobin->publish($event);
        }
    }

    public function testDispatcherHandlesEmptyDispatcherList()
    {
        $roundrobin = new RoundRobinPublisher();

        $roundrobin->publish(new Event('test'));

        $this->assertTrue(true, 'Just checking no errors are raised.');
    }
}
