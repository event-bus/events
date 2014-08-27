<?php

namespace Aztech\Events\Bus\Transport\Socket;

use Aztech\Events\Bus\Transport;
use Aztech\Events\Event;

class SocketTransport implements Transport
{

    private $socket;

    public function __construct(Wrapper $socket)
    {
        $this->socket = $socket;
    }

    public function write(Event $event, $serializedRepresentation)
    {
        $this->socket->writeRaw($serializedRepresentation);
    }

    public function read()
    {
        return $this->socket->readRaw();
    }
}
