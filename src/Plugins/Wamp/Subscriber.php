<?php

namespace Aztech\Events\Plugins\Wamp;

use Aztech\Events\Event;
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
        return $event instanceof \Aztech\Events\Bus\Event && $event->getCategory() == 'publish';
    }
}
