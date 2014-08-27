<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq\ChannelReader;

use Aztech\Events\Bus\Channel\ChannelReader;
use Aztech\Events\Bus\Plugins\ZeroMq\SocketWrapper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class Subscribe implements ChannelReader, LoggerAwareInterface
{

    /**
     * Var is declared as ZMQSocket for autocomplete, but is actually a SocketWrapper.
     *
     * @var \ZMQSocket
     */
    private $socket;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(SocketWrapper $wrapper)
    {
        $this->socket = $wrapper;
        $this->logger = $logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Sets the subscription prefix filter.
     * If set to a non empty value,
     * received messages that do not begin with the prefix filter
     * are ignored.
     * 
     * @param string $prefix            
     */
    public function subscribeTo($prefix = '')
    {
        $this->socket->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, (string)$prefix);
    }

    public function read()
    {
        $this->pullSocket->bindIfNecessary();
        $data = $this->pullSocket->recv();
        
        $this->logger->debug(sprintf('Read %d characters, returning.', strlen($data), ['data' => $data]));
        
        return $data;
    }
}
