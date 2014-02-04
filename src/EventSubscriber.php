<?php

namespace Evaneos\Events;

interface EventSubscriber
{
    public function handle(Event $event);
}
