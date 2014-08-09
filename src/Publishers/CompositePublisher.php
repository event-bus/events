<?php

namespace Evaneos\Events\Publishers;

use Evaneos\Events\EventPublisher;
use Evaneos\Events\Event;

class CompositePublisher implements EventPublisher
{
    /**
     *
     * @var EventPublisher[]
     */
    private $publishers;

    public function __construct()
    {
        $this->publishers = new \SplObjectStorage();
    }

    public function addPublisher(EventPublisher $publisher)
    {
        if (! $this->publishers->contains($publisher)) {
            $this->publishers->attach($publisher);
        }
    }

    public function removePublisher(EventPublisher $publisher)
    {
        if ($this->publishers->contains($publisher)) {
            $this->publishers->detach($publisher);
        }
    }

    public function publish(Event $event)
    {
        foreach ($this->publishers as $publisher)
        {
            $publisher->publish($event);
        }
    }

}
