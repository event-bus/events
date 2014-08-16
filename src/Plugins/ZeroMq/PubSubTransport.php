<?php

namespace Aztech\Events\Plugins\ZeroMq;

use Aztech\Events\Event;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PubSubTransport implements \Aztech\Events\Transport, LoggerAwareInterface
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

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(SocketWrapper $pushSocket, SocketWrapper $pullSocket, LoggerInterface $logger)
    {
        $this->logger = $logger ?  : new NullLogger();
        
        $this->pullSocket = $pullSocket;
        // Disable prefix filtering
        $this->pullSocket->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, '');
        
        $this->pushSocket = $pushSocket;
    }

    public function __destruct()
    {
        $this->pushSocket = null;
        $this->pullSocket = null;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function read()
    {
        $this->pullSocket->bindIfNecessary();
        $data = $this->pullSocket->recv();
        
        $this->logger->debug(sprintf('Read %d characters, returning.', strlen($data), ['data' => $data]));
        
        return $data;
    }

    public function write(Event $event, $serializedEvent)
    {
        $this->pushSocket->connectIfNecessary();
        $this->pushSocket->send($serializedEvent);
        
        $this->logger->debug(sprintf('Wrote %d characters to socket.', strlen($serializedEvent)), ['data' => $serializedEvent]);
    }
}
