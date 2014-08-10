<?php

namespace Evaneos\Events\Processors\RabbitMQ;

class EventStatusWampServer
{

    private $processor;

    public function __construct(StompStatusProcessor $processor)
    {
        $this->processor = $processor;
    }

}
