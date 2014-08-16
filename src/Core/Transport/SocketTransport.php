<?php

namespace Aztech\Events\Core\Transport;

use Aztech\Events\Transport;
use Aztech\Events\Event;
use Aztech\Events\Core\Transport\Socket\Wrapper;

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
