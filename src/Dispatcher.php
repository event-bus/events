<?php

namespace Aztech\Events;

interface Dispatcher
{

    public function addListener($category, Subscriber $subscriber);

    public function dispatch(Event $event);
}
