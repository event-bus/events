<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Tests\Core\Transport\Socket\SocketWrapperTest;

class Transport implements \Aztech\Events\Transport
{

    private $pushSocket;

    private $pullSocket;

    public function __construct(SocketWrapper $pushSocket, SocketWrapper $pullSocket)
    {
        $this->pullSocket = $pullSocket;
        $this->pushSocket = $pushSocket;
    }

    public function read()
    {
        $this->pullSocket->bindIfNecessart();

        return $this->pullSocket->recv();
    }

    public function write(Event $event, $serializedEvent)
    {
        $this->pullSocket->bindIfNecessart();

        $this->pushSocket->send($serializedEvent);
    }

}
