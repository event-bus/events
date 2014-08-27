<?php

namespace Aztech\Events\Bus;

interface Publisher
{

    public function publish(\Aztech\Events\Event $event);
}
