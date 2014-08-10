<?php

namespace Evaneos\Events;

class StatusEvent extends AbstractEvent
{

    protected $name;

    protected $event;

    protected $time;

    public function __construct($name, Event $event)
    {
        $this->name = $name;
        $this->event = $event;
        $this->time = microtime(true);
    }

    public function getCategory()
    {
        return 'event-notify.' . $this->name;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getTime()
    {
        return $this->time;
    }
}
