<?php

namespace Aztech\Events;

interface Transport
{

    function write(Event $event, $serializedRepresentation);

    function read();

}
