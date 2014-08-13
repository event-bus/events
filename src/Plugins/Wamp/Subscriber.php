<?php

namespace Aztech\Events\Plugins\Wamp;

use Aztech\Events\Event;
use Aztech\Events\Core\AbstractEvent;
use Ratchet\Wamp\Topic;

class Subscriber implements \Aztech\Events\Subscriber
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
        return $event instanceof \Aztech\Events\Core\Event && $event->getCategory() == 'publish';
    }
}
