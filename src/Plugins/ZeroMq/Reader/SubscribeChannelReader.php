<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq\Reader;

use Aztech\Events\Bus\Channel\ChannelReader;
use Aztech\Events\Bus\Plugins\ZeroMq\ZeroMqSocketWrapper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class SubscribeChannelReader implements ChannelReader, LoggerAwareInterface
{

    /**
     *
     * @var ZeroMqSocketWrapper
     */
    private $subscriber;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ZeroMqSocketWrapper $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Sets the subscription prefix filter. If set to a non empty value, received messages that do not begin with the prefix filter are ignored.
     *
     * @param string $prefix
     */
    public function subscribeTo($prefix = '')
    {
        $this->subscriber->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, (string)$prefix);
    }

    public function read()
    {
        $this->subscriber->bindIfNecessary();
        $data = $this->subscriber->recv();

        $this->logger->debug(sprintf('Read %d characters, returning.', strlen($data), [
            'data' => $data
        ]));

        return $data;
    }
}
