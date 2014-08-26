<?php

namespace Aztech\Events\Tests\Bus\Publisher;

use Aztech\Events\Bus\Publisher\BroadcastPublisher;
use Aztech\Events\Bus\Event;

class BroadcastPublisherTest extends \PHPUnit_Framework_TestCase
{

    private function getPublisherMock()
    {
        return $this->getMock('\Aztech\Events\Publisher');
    }

    public function testPublisherDefersPublishToAllPublisher()
    {
        $publishers = array(
            $this->getPublisherMock(),
            $this->getPublisherMock()
        );
        $event = new Event('test');
        $broadcaster = new BroadcastPublisher();

        foreach ($publishers as $publisher) {
            $publisher->expects($this->once())
                ->method('publish')
                ->with($event);

            $broadcaster->addPublisher($publisher);
        }

        $broadcaster->publish($event);
    }
}
