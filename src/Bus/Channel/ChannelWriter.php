<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Event;

interface ChannelWriter
{

    function write(Event $event, $serializedData);
}
