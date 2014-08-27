<?php

namespace Aztech\Events\Bus\Plugins\Stomp;

use FuseSource\Stomp\Stomp;
use Aztech\Events\Bus\Channel\ChannelReader;

class StompChannelReader implements ChannelReader
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
}
