<?php

namespace Aztech\Events\Core;

use Aztech\Events\Subscriber;
use Aztech\Util\Timer\Timer;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Dispatcher implements \Aztech\Events\Dispatcher, LoggerAwareInterface
{

    /**
     *
     * @var CategorySubscription[]
     */
    private $subscriptions = array();

    private $logger = null;

    private $timer;

    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->timer = new Timer();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * (non-PHPdoc)
     * @see \Aztech\Events\Dispatcher::addListener()
     */
    public function addListener($category, Subscriber $subscriber)
    {
        $this->subscriptions[] = new CategorySubscription($category, $subscriber);
        $this->logger->debug('Registered new subcriber of class "' . get_class($subscriber) . '" using filter "' . $category . '".');
    }

    public function dispatch(Event $event)
    {
        $this->logger->info('[ "' . $event->getId() . '" ] Starting event dispatch to ' . count($this->subscriptions) . ' potential subscribers.');

        $this->timer->reset();
        $this->timer->start();

        $category = $event->getCategory();
        $dispatchCount = 0;

        foreach ($this->subscriptions as $subscription) {
            $result = $this->tryDispatch($subscription, $event);
            $dispatchCount += (int)$result;
        }

        $this->timer->stop();
        $this->logger->info('[ "' . $event->getId() . '" ] Dispatch to ' . $dispatchCount . ' subscribers done in ' . $this->timer->getElapsed() . ' s.');
    }

    /**
     *
     * @param CategorySubscription $subscription
     * @param Event $event
     * @return boolean True if dispatch was successful, false otherwise
     */
    private function tryDispatch(CategorySubscription $subscription, Event $event)
    {
        $dispatched = false;

        try {
            $hasMatch = $subscription->matches($event->getCategory());

            if ($hasMatch && $subscription->getSubscriber()->supports($event)) {
                $this->logger->debug('[ "' . $event->getId() . '" ] Dispatched to ' . get_class($subscription->getSubscriber()));
                $subscription->getSubscriber()->handle($event);
                $dispatched = true;
            }
            elseif (! $hasMatch) {
                $this->logger->debug('[ "' . $event->getId() . '" ] No match for filter value "' . $subscription->getCategoryFilter() . '"');
            }
            else {
                $this->logger->debug('[ "' . $event->getId() . '" ] Validated match, but event was rejected by subscriber ' . get_class($subscription->getSubscriber()) . '.');
            }
        }
        catch (\Exception $ex) {
            $this->logger->error('[ "' . $event->getId() . '" ] Event dispatch error', array(
                'subscription-filter' => $subscription->getCategoryFilter(),
                'subscriber_class' => get_class($subscription->getSubscriber()),
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ));
        }

        return $dispatched;
    }
}
