<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Event;

interface ChannelWriter
{

    /**
     *
     * @param Event $event
     * @param string $serializedData
     */
    function write(Event $event, $serializedData);
}
