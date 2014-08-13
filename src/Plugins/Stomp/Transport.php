<?php

namespace Aztech\Events\Plugins\Stomp;

use FuseSource\Stomp\Stomp;
use Aztech\Events\Event;

class Transport implements \Aztech\Events\Transport
{

    private $queueName;

    private $stomp;

    private $subscribed = false;

    public function __construct(Stomp $client, $queueName)
    {
        $this->queueName = $queueName;
        $this->stomp = $client;
    }

    public function read()
    {
        if (! $this->subscribed) {
            $this->stomp->subscribe($this->queueName);
            $this->subscribed = true;
        }

        $message = $this->stomp->readFrame();

        return $message;
    }

    public function write(Event $event, $serializedRepresentation)
    {
        $this->stomp->send($this->queueName, $serializedRepresentation);
    }
}
