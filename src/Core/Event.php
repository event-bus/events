<?php

namespace Aztech\Events\Core;

class Event extends AbstractEvent
{

    private $properties = array();

    private $category = '';

    public function __construct($category, array $properties)
    {
        parent::__construct();

        $this->category = $category;
        $this->properties = $properties;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function __get($name)
    {
        if (! array_key_exists($name, $this->properties)) {
            return null;
        }

        return $this->properties[$name];
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }
}
