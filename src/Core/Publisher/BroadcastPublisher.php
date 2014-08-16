<?php

namespace Aztech\Events\Core\Publisher;

use Aztech\Events\Event;
use Aztech\Events\Publisher;

class BroadcastPublisher extends AbstractPublisherCollection implements Publisher
{

    public function publish(Event $event)
    {
        foreach ($this->publishers as $publisher) {
            $publisher->publish($event);
        }
    }
}
