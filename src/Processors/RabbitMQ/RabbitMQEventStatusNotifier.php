<?php

namespace Evaneos\Events\Processors\RabbitMQ;

use Evaneos\Events\EventSubscriber;
use Evaneos\Events\Event;
use Evaneos\Events\EventProcessor;
use Evaneos\Events\StatusEvent;
use Evaneos\Events\EventPublisher;

class RabbitMQEventStatusNotifier implements EventSubscriber
{

    private $publisher;

    public function __construct(EventPublisher $publisher)
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
