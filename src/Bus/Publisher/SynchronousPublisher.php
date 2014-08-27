<?php

namespace Aztech\Events\Bus\Publisher;

use Aztech\Events\Bus\Publisher;
use Aztech\Events\Callback;
use Aztech\Events\Dispatcher;
use Aztech\Events\Event;
use Aztech\Events\EventDispatcher;
use Aztech\Events\Subscriber;

/**
 * Publisher/consumer that simply wraps a Dispatcher.
 * @author thibaud
 *
 */
class SynchronousPublisher implements Publisher
{

    /**
     *
     * @var Dispatcher
     */
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher = null)
    {
        $this->dispatcher = $dispatcher ?: new EventDispatcher();
    }

    public function publish(Event $event)
    {
        return $this->dispatcher->dispatch($event);
    }

    public function processNext(Dispatcher $dispatcher)
    {
        // Raise warning ?
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Events\Bus\Consumer::on()
     */
    public function on($categoryFilter, $subscriber)
    {
        if (is_callable($subscriber)) {
            $subscriber = new Callback($subscriber);
        }
        elseif (! ($subscriber instanceof Subscriber)) {
            throw new \InvalidArgumentException('Subscriber must a be a callable or an instance of Subscriber.');
        }

        $this->dispatcher->addListener($categoryFilter, $subscriber);
    }
}
