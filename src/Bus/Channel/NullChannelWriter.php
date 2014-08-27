<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Event;

class NullChannelWriter implements ChannelWriter
{

    function write(Event $event, $serializedData)
    {
        // Do nothing.
    }
}
