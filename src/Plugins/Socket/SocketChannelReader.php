<?php

namespace Aztech\Events\Bus\Plugins\Socket;

use Aztech\Events\Bus\Channel\ChannelReader;

class SocketChannelReader implements ChannelReader
{

    private $socket;

    public function __construct(SocketWrapper $socket)
    {
        $this->socket = $socket;
    }

    public function read()
    {
        return $this->socket->readRaw();
    }
}
