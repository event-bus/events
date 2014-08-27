<?php

namespace Aztech\Events\Bus\Transport;

use Aztech\Events\Event;

interface Writer
{

    function write(Event $event, $serializedData);
}
