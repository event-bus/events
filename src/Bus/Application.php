<?php

namespace Aztech\Events\Bus;

use Aztech\Events\Bus\Processor;
use Aztech\Events\Callback;
use Aztech\Events\Subscriber;
use Aztech\Events\Dispatcher;

class Application implements Processor
{

    private $processor;

    private $dispatcher;

    public function __construct(Processor $processor, Dispatcher $dispatcher)
    {
        $this->processor = $processor;
        $this->dispatcher = $dispatcher;
    }

    public function on($filter, $subscriber)
    {
        if (is_callable($subscriber) && ! ($subscriber instanceof Subscriber)) {
            $subscriber = new Callback($subscriber);
        }
        elseif (! ($subscriber instanceof Subscriber)) {
            throw new \InvalidArgumentException('Subscriber must be a callable or a Subscriber instance.');
        }

        $this->dispatcher->addListener($filter, $subscriber);
    }

    public function run()
    {
        while (true) {
            $this->processNext($this->dispatcher);
        }
    }

    public function processNext(Dispatcher $dispatcher)
    {
        $this->processor->processNext($dispatcher);
    }
}
