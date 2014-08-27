<?php

namespace Aztech\Events\Bus\Plugins\Wamp;

use Aztech\Events\Event;
use Ratchet\Wamp\Topic;

class WampTopicSubscriber implements \Aztech\Events\Subscriber
{

    private $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function handle(Event $event)
    {
        $properties = $event->getProperties();

        $this->topic->broadcast($properties['data']);
    }

    public function supports(Event $event)
    {
        return true;
    }
}
