<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Tests\Bus\Transport\Socket\SocketWrapperTest;
use Aztech\Events\Event;

class Transport implements \Aztech\Events\Transport
{

    /**
     *
     * @var \ZMQSocket
     */
    private $pushSocket;

    /**
     *
     * @var \ZMQSocket
     */
    private $pullSocket;

    public function __construct(SocketWrapper $pushSocket, SocketWrapper $pullSocket)
    {
        $this->pullSocket = $pullSocket;
        $this->pushSocket = $pushSocket;
    }

    public function __destruct()
    {
        $this->pushSocket = null;
        $this->pullSocket = null;
    }

    public function read()
    {
        $this->pullSocket->connectIfNecessary();
        $this->pullSocket->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, '');

        //echo 'Reading...' . PHP_EOL;

        $add = $this->pullSocket->recv();
        $data = $this->pullSocket->recv();

        //echo microtime(true) . ' Read ' . $data . PHP_EOL;

        return $data;
    }

    public function write(Event $event, $serializedEvent)
    {
        $this->pushSocket->connectIfNecessary();

        //echo 'Sending ' . $serializedEvent . PHP_EOL;

        $this->pushSocket->send("E" . $serializedEvent);

        //echo 'Sent' . PHP_EOL;
    }
}
