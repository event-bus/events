<?php

namespace Aztech\Events\Bus\Publisher;

use Aztech\Events\Event;
use Aztech\Events\Publisher;

class RoundRobinPublisher extends AbstractPublisherCollection implements Publisher
{
    private $queue;

    public function __construct(array $publishers = array())
    {
        parent::__construct($publishers);

        $this->queue = new \SplStack();
    }

    public function addPublisher(Publisher $publisher)
    {
        $this->queue->push($publisher);

        parent::addPublisher($publisher);
    }

    public function publish(Event $event)
    {
        if (! $this->publishers->count()) {
            return;
        }

        $publisher = $this->queue->shift();

        while (! $this->publishers->contains($publisher)) {
            $publisher = $this->queue->shift();
        }

        $publisher->publish($event);

        $this->queue->push($publisher);
    }
}
