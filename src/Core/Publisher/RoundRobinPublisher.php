<?php

namespace Aztech\Events\Core\Publisher;

use Aztech\Events\Event;
use Aztech\Events\Publisher;

class RoundRobinPublisher extends AbstractPublisherCollection implements Publisher
{
    public function __construct(array $publishers = array())
    {
        parent::__construct($publishers);

        reset($this->publishers);
    }

    public function publish(Event $event)
    {
        if (! ($publisher = next($this->publishers))) {
            $publisher = reset($this->publishers);
        }

        if (! $publisher) {
            // No registered publishers
            return;
        }

        $publisher->publish($event);
    }
}
