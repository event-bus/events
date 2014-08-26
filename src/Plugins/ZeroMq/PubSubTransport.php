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
    private $subscriber;

    /**
     *
     * @var \ZMQSocket
     */
    private $publisher;

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(SocketWrapper $publisher, SocketWrapper $subscriber, LoggerInterface $logger)
    {
        $this->logger = $logger ?  : new NullLogger();

        $this->publisher = $publisher;
        $this->subscriber = $subscriber;
        $this->subscriber->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, 'E');
    }

    public function __destruct()
    {
        $this->publisher = null;
        $this->subscriber = null;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function read()
    {
        $this->subscriber->bindIfNecessary();
        $data = $this->subscriber->recv();

        $this->logger->debug(sprintf('Read %d characters, returning.', strlen($data), ['data' => $data]));

        return substr($data, 1);
    }

    public function write(Event $event, $serializedEvent)
    {
        $this->publisher->connectIfNecessary();
        $this->publisher->send('E' . $serializedEvent);

        $this->logger->debug(sprintf('Wrote %d characters to socket.', strlen($serializedEvent)), ['data' => $serializedEvent]);
    }
}
