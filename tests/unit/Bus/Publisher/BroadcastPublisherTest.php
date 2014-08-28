<?php

namespace Aztech\Events\Tests\Bus\Publisher;

use Aztech\Events\Bus\Publisher\BroadcastPublisher;
use Aztech\Events\Bus\Event;

class BroadcastPublisherTest extends \PHPUnit_Framework_TestCase
{

    private function getPublisherMock()
    {
        return $this->getMock('\Aztech\Events\Bus\Publisher');
    }

    public function testPublisherDefersPublishToAllPublisher()
    {
        $publishers = array(
            $this->getPublisherMock(),
            $this->getPublisherMock()
        );
        $event = new Event('test');

        foreach ($publishers as $publisher) {
            $publisher->expects($this->once())
                ->method('publish')
                ->with($event);
        }

        $broadcaster = new BroadcastPublisher($publishers);

        $broadcaster->publish($event);
    }
}
