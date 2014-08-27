<?php

namespace Aztech\Events\Bus\Publisher;

use Aztech\Events\Event;
use Aztech\Events\Bus\Publisher;

class BroadcastPublisher extends AbstractPublisherCollection implements Publisher
{

    public function publish(Event $event)
    {
        foreach ($this->publishers as $publisher) {
            $publisher->publish($event);
        }
    }
}
