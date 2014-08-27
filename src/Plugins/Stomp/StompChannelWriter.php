<?php

namespace Aztech\Events\Bus\Plugins\Stomp;

use FuseSource\Stomp\Stomp;
use Aztech\Events\Event;
use Aztech\Events\Bus\Channel\ChannelWriter;

class StompChannelWriter implements ChannelWriter
{

    private $queueName;

    private $stomp;

    private $subscribed = false;

    public function __construct(Stomp $client, $queueName)
    {
        $this->queueName = $queueName;
        $this->stomp = $client;
    }

    public function write(Event $event, $serializedRepresentation)
    {
        $this->stomp->send($this->queueName, $serializedRepresentation);
    }
}
