<?php

namespace Aztech\Events;

interface Dispatcher
{

    /**
     * @return void
     */
    public function addListener($category, Subscriber $subscriber);

    /**
     * @return void
     */
    public function dispatch(Event $event);
}
