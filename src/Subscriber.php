<?php

namespace Aztech\Events;

interface Subscriber
{

    public function handle(Event $event);

    public function supports(Event $event);
}
