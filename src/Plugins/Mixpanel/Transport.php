<?php

namespace Aztech\Events\Plugins\Mixpanel;

use Aztech\Events\Event;
use Aztech\Events\Bus\AbstractEvent;

class Transport implements \Aztech\Events\Transport
{

    private $mixpanel;

    public function __construct(\Mixpanel $mixpanel)
    {
        $this->mixpanel = $mixpanel;
    }

    public function read()
    {
        throw new \BadMethodCallException('Read not supported.');
    }

    public function write(Event $event, $serializedEvent)
    {
        $properties = array();

        if ($event instanceof AbstractEvent) {
            $properties = $event->getProperties();
        }

        $this->mixpanel->track($event->getCategory(), $properties);
    }
}
