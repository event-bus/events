<?php

namespace Aztech\Events\Bus;

use Rhumsaa\Uuid\Uuid;
use Aztech\Events\Event as EventInterface;

abstract class AbstractEvent implements EventInterface
{

    /**
     *
     * @var string
     */
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId()
    {
        return $this->getCategory() . ':' . $this->id;
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
