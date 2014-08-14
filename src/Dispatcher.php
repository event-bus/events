<?php

namespace Aztech\Events;

interface Dispatcher
{

    /**
     * @param string $category
     * @param Subscriber $subscriber
     * @return void
     */
    public function addListener($category, Subscriber $subscriber);

    /**
     * @param Event $event
     * @return void
     */
    public function dispatch(Event $event);
}
