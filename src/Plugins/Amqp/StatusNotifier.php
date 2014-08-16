<?php

namespace Aztech\Events\Plugins\Amqp;

use Aztech\Events\Subscriber;
use Aztech\Events\Publisher;
use Aztech\Events\Event;
use Aztech\Events\Providers\Simple\StatusEvent;

class StatusNotifier implements Subscriber
{

    private $publisher;

    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function supports(Event $event)
    {
        return ($event instanceof StatusEvent);
    }

    public function handle(Event $event)
    {
        $this->publisher->publish($event);
    }
}
