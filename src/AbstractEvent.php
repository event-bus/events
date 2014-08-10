<?php

namespace Evaneos\Events;

abstract class AbstractEvent implements Event
{

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
