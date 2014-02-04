<?php
namespace Evaneos\Events;

interface Event
{

    /**
     * Returns the category of the event.
     */
    public function getCategory();
}
