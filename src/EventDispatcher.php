<?php
namespace Evaneos\Events;

interface EventDispatcher
{

    public function addListener($category, EventSubscriber $subscriber);
}
