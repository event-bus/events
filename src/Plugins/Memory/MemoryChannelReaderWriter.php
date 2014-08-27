<?php

namespace Aztech\Events\Bus\Plugins\Memory;

use Aztech\Events\Bus\Channel\ChannelReader;
use Aztech\Events\Bus\Channel\ChannelWriter;
use Aztech\Events\Event;

class MemoryChannelReaderWriter implements ChannelReader, ChannelWriter
{

    private $events = array();

    public function write(Event $event, $serializedData)
    {
        $this->events[] = $serializedData;
    }

    public function read()
    {
        return array_pop($this->events);
    }
}
