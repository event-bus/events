<?php

namespace Aztech\Events\Core\Subscriber;

use Aztech\Events\Event;
use Aztech\Events\Publisher;
use Aztech\Events\Subscriber;
use Aztech\Events\Util\TrieMatcher\Trie;

class PublishingSubscriber implements Subscriber
{

    private $publisher;

    private $constraint;

    private $matcher;

    public function __construct(Publisher $publisher, $constraint = '#')
    {
        $this->publisher = $publisher;
        $this->constraint = $constraint;

        // Use a trie instead of a CategoryMatcher to cache the lookup tree.
        $this->matcher = new Trie($constraint);
    }

    public function supports(Event $event)
    {
        return $this->matcher->matches($event->getCategory());
    }

    public function handle(Event $event)
    {
        return $this->publisher->publish($event);
    }
}
