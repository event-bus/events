<?php

namespace Evaneos\Events\Providers\Wamp;

use Evaneos\Events\EventSubscriber;
use Evaneos\Events\Serializer;
use Ratchet\Wamp\Topic;
use Evaneos\Events\Event;

class TopicSubscriber implements EventSubscriber
{

    private $topic;

    private $serializer;

    public function __construct(Topic $topic, Serializer $serializer)
    {
        $this->topic = $topic;
        $this->serializer = $serializer;
    }

    public function handle(Event $event)
    {
        $serialized = $this->serializer->serialize($event);

        $this->topic->broadcast($serialized);
    }

    public function supports(Event $event)
    {
        return true;
    }
}
