<?php

namespace Aztech\Events\Bus\Transport;

use Aztech\Events\Event;

class NullWriter implements Writer
{

    function write(Event $event, $serializedData)
    {
        // Do nothing.
    }
}
