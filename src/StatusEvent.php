<?php

namespace Evaneos\Events;

class StatusEvent implements Event
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

    public function getProperties()
    {
        return get_object_vars($this);
    }

    public function setProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            $this->{$name} = $value;
        }
    }
}
