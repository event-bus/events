<?php

namespace Evaneos\Events\Providers\Simple;

class SimpleEvent extends AbstractEvent
{

    private $properties = array();

    private $category = '';

    public function __construct($category, array $properties)
    {
        $this->category = $category;
        $this->properties = $properties;
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
