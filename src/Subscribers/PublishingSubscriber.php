<?php

namespace Evaneos\Events\Subscribers;

use Evaneos\Events\EventSubscriber;
use Ratchet\Wamp\WampServerInterface;
use Evaneos\Events\Event;
use Evaneos\Events\StatusEvent;
use Evaneos\Events\Serializer;
use Evaneos\Events\EventSerializer;
use Evaneos\Events\EventPublisher;
use Evaneos\Events\CategoryMatcher;

class PublishingSubscriber implements EventSubscriber
{

    private $publisher;

    private $constraint;

    private $matcher;

    public function __construct(EventPublisher $publisher, $constraint = '#')
    {
        $this->publisher = $publisher;
        $this->constraint = $constraint;
        $this->matcher = new CategoryMatcher();
    }

    public function supports(Event $event)
    {
        return $this->matcher->checkMatch($this->constraint, $event->getCategory());
    }

    public function handle(Event $event)
    {
        return $this->publisher->publish($event);
    }
}
