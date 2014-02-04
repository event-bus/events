<?php

namespace Evaneos\Events;

interface EventPublisher
{

    public function publish(Event $event);
}
