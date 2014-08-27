<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Dispatcher;
use Aztech\Events\Bus\Serializer;
use Aztech\Events\Bus\Processor;

class ChannelProcessor implements Processor
{

    private $reader;

    private $serializer;

    public function __construct(ChannelReader $reader, Serializer $serializer)
    {
        $this->reader = $reader;
        $this->serializer = $serializer;
    }

    public function processNext(Dispatcher $dispatcher)
    {
        $next = $this->reader->read();

        if ($next === null) {
            return;
        }

        $event = $this->serializer->deserialize($next);

        if (! $event) {
            return;
        }

        $dispatcher->dispatch($event);
    }
}
