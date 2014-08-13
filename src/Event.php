<?php

namespace Aztech\Events;

interface Event
{

    /**
     * Returns the event ID.
     * @return string
     */
    public function getId();

    /**
     * Returns the category of the event.
     * @return string
     */
    public function getCategory();
}
