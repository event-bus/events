<?php

namespace Evaneos\Events\Providers\Stomp;

use Evaneos\Events\AbstractProcessor;
use Evaneos\Events\EventDispatcher;
use FuseSource\Stomp\Stomp;
use Evaneos\Events\Serializer;

class EventProcessor extends AbstractProcessor
{

    private $stomp;

    private $queueName;

    private $username;

    private $password;

    private $subscribed;

    private $serializer;

    public function __construct(Stomp $stomp, Serializer $serializer, $queueName, $username = '', $password = '')
    {
        $this->stomp = $stomp;
        $this->serializer = $serializer;
        $this->username = $username;
        $this->password = $password;
        $this->queueName = $queueName;
        $this->subscribed = false;
    }

    public function processNext(EventDispatcher $dispatcher)
    {
        if (! $this->stomp->isConnected()) {
            $this->stomp->connect($this->username, $this->password);
            $this->subscribed = false;
        }

        if (! $this->subscribed) {
            $this->stomp->subscribe($this->queueName);
            $this->subscribed = true;
        }

        $message = $this->stomp->readFrame();
        $event = $this->serializer->unserialize($object);

        $dispatcher->dispatch($event);
    }

}
