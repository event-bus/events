<?php

namespace Aztech\Events\Transport;

use Aztech\Events\Event;

interface Writer
{

    function write(Event $event, $serializedData);
}
