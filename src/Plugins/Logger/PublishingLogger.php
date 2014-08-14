<?php

namespace Aztech\Events\Plugins\Logger;

use Psr\Log\AbstractLogger;
use Aztech\Events\Publisher;

class PublishingLogger extends AbstractLogger
{

    private $publisher;

    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function log($level, $message, $context)
    {
        $event = new Event('log.' . $level, array('message' => $message, 'context' => $context));

        $this->publisher->publish($event);
    }

}
