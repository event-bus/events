<?php

namespace Aztech\Events\Bus\Plugins\Socket;

use Aztech\Events\Event;
use Aztech\Events\Bus\Channel\ChannelReader;

class SocketChannelWriter implements ChannelReader
{

    private $socket;

    public function __construct(Wrapper $socket)
    {
        $this->socket = $socket;
    }

    public function write(Event $event, $serializedData)
    {
        return $this->socket->writeRaw($serializedData);
    }
}
