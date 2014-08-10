<?php

namespace Evaneos\Events;

interface EventSubscriber
{

    public function handle(Event $event);

    public function supports(Event $event);
}
