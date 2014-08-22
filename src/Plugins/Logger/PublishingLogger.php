<?php

namespace Aztech\Events\Plugins\Logger;

use Psr\Log\AbstractLogger;
use Aztech\Events\Publisher;
use Aztech\Events\Bus\Event;

class PublishingLogger extends AbstractLogger
{

    private $publisher;

    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function log($level, $message, array $context = array())
    {
        $event = new Event('log.' . $level, array('message' => $message,'context' => $context));
        
        $this->publisher->publish($event);
    }
}
