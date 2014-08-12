<?php

namespace Evaneos\Events;

class SimpleConsumer implements EventConsumer
{

    private $processor;

    private $dispatcher;

    public function __construct(EventProcessor $processor, EventDispatcher $dispatcher)
    {
        $this->processor = $processor;
        $this->dispatcher = $dispatcher;
    }

    public function on($filter, $subscriber)
    {
        $this->processor->on($filter, $subscriber);
    }

    public function consumeAll()
    {
        while (true) {
            $this->consumeNext();
        }
    }

    public function consumeNext()
    {
        $this->processor->processNext($this->dispatcher);
    }

}
