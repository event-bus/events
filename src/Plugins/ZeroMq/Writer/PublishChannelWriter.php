<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq\Writer;

use Aztech\Events\Bus\Channel\ChannelWriter;
use Aztech\Events\Event;
use Aztech\Events\Bus\Plugins\ZeroMq\ZeroMqSocketWrapper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PublishChannelWriter implements ChannelWriter, LoggerAwareInterface
{

    /**
     *
     * @var ZeroMqSocketWrapper
     */
    private $publisher;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ZeroMqSocketWrapper $publisher)
    {
        $this->publisher = $publisher;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function write(Event $event, $serializedEvent)
    {
        $this->publisher->connectIfNecessary();
        $this->publisher->send($serializedEvent);

        $this->logger->debug(sprintf('Wrote %d characters to socket.', strlen($serializedEvent)), [
            'data' => $serializedEvent
        ]);
    }
}
