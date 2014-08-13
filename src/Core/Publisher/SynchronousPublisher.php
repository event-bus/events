<?php

namespace Aztech\Events\Core\Publisher;

use Aztech\Events\Dispatcher;
use Aztech\Events\Event;
use Aztech\Events\Publisher;
use Aztech\Events\Subscriber;
use Aztech\Events\Consumer;

class SynchronousPublisher implements Publisher, Consumer
{

    /**
     *
     * @var \Aztech\Events\EventDispatcher
     */
    private $dispatcher;

    /**
     *
     * @var EventQueueManager
     */
    private $queueManager;

    public function __construct(Dispatcher $dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?: new \Aztech\Events\Core\Dispatcher();
    }

    public function publish(Event $event)
    {
        return $this->dispatcher->dispatch($event);
    }

    public function processNext(Dispatcher $dispatcher)
    {
        // Raise warning ?
    }

    public function on($categoryFilter, $subscriber)
    {
        if (! is_callable($subscriber) && ! ($subscriber instanceof Subscriber)) {
            throw new \InvalidArgumentException('Subscriber must a be a callable or an instance of Subscriber.');
        }

        $this->dispatcher->addListener($categoryFilter, $subscriber);
    }

    public function consumeAll()
    {
        // Raise warning ?
    }

    public function consumeNext()
    {
        // Raise warning ?
    }
}
