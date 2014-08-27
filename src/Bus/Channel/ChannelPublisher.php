<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Bus\Channel\ChannelWriter;
use Aztech\Events\Bus\Publisher;
use Aztech\Events\Bus\Serializer;
use Aztech\Events\Event;

class ChannelPublisher implements Publisher
{

    private $serializer;

    private $writer;

    public function __construct(ChannelWriter $writer, Serializer $serializer)
    {
        $this->writer = $writer;
        $this->serializer = $serializer;
    }

    public function publish(Event $event)
    {
        $serializedRepresentation = $this->serializer->serialize($event);

        return $this->writer->write($event, $serializedRepresentation);
    }
}
