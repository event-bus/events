<?php

namespace Aztech\Events\Util;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Aztech\Events\Event;

class EventLogger extends AbstractLogger
{

    private $event;

    private $logger;

    public function __construct(LoggerInterface $logger, Event $event)
    {
        $this->logger = $logger;
        $this->event = $event;
    }

    public function log($level, $message, array $context = array())
    {
        $prefix = '[ "' . $this->event->getId() . '" ] ';

        return $this->logger->log($level, $prefix . $message, $context);
    }
}
