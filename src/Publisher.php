<?php

namespace Aztech\Events;

interface Publisher
{

    public function publish(Event $event);
}
