<?php

namespace Aztech\Events;

interface Transport
{

    /**
     *
     * @return void
     */
    function write(Event $event, $serializedRepresentation);

    function read();
}
