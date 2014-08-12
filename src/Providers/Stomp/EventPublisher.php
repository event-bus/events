<?php

namespace Evaneos\Events\Providers\Stomp;

use Evaneos\Events\EventPublisher;
use Evaneos\Events\EventSerializer;
use FuseSource\Stomp\Stomp;

class EventPublisher implements EventPublisher
{

    private $serializer;

    private $stomp;

    private $queue;

    public function __construct(EventSerializer $serializer, array $options)
    {
        $this->serializer = $serializer;
        $this->queue = $options['queue-name'];

        $brokerUri = 'tcp://' . $options['host'] . ':' . $options['port'];
        $stomp = new Stomp($brokerUri);
        $stomp->connect($options['user'], $options['pass']);

        $this->stomp = $stomp;
    }

    public function publish(Event $event)
    {
        $serialized = $this->serializer->seralize($event);

        $this->stomp->send($this->queue, $serialized);
    }
}
