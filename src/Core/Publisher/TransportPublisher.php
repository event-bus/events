<?php

namespace Aztech\Events\Core\Publisher;

use Aztech\Events\Publisher;
use Aztech\Events\Transport;
use Aztech\Events\Serializer;
use Aztech\Events\Event;

class TransportPublisher implements Publisher
{

    private $serializer;

    private $transport;

    public function __construct(Transport $transport, Serializer $serializer)
    {
        $this->transport = $transport;
        $this->serializer = $serializer;
    }

    public function __destruct()
    {
        $this->transport = null;
        $this->serializer = null;
    }

    public function publish(Event $event)
    {
        $serializedRepresentation = $this->serializer->serialize($event);

        return $this->transport->write($event, $serializedRepresentation);
    }
}
