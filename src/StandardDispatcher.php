<?php

namespace Evaneos\Events;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Aztech\Util\Timer\Timer;

class StandardDispatcher implements EventDispatcher, LoggerAwareInterface
{
    /**
     *
     * @var CategorySubscription[]
     */
    private $subscriptions = array();

    private $logger = null;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function addListener($category, EventSubscriber $subscriber)
    {
        $this->subscriptions[] = new CategorySubscription($category, $subscriber);
    }

    public function dispatch(Event $event)
    {
        $timer = new Timer();
        $timer->start();

        $this->logger->info('Dispatching event category : ' . $event->getCategory());

        $category = $event->getCategory();

        foreach ($this->subscriptions as $subscription) {
            $hasMatch = $subscription->matches($category);

            if ($hasMatch && $subscription->getSubscriber()->supports($event)) {
                $this->logger->debug('Dispatched to ' . get_class($subscription->getSubscriber()));
                $subscription->getSubscriber()->handle($event);
            }
        }

        $timer->stop();
        $this->logger->info('Dispatch done in ' . $timer->getElapsed() . ' s.');
    }
}
