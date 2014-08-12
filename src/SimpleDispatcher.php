<?php

namespace Evaneos\Events;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Aztech\Util\Timer\Timer;

class SimpleDispatcher implements EventDispatcher, LoggerAwareInterface
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
        $this->logger->info('[ "' . $event->getId() . '" ] Starting event dispatch to ' . count($this->subscriptions) . ' potential subscribers.');

        $timer = new Timer();
        $timer->start();

        $category = $event->getCategory();

        foreach ($this->subscriptions as $subscription) {
            try {
                $hasMatch = $subscription->matches($category);

                if ($hasMatch && $subscription->getSubscriber()->supports($event)) {
                    $this->logger->debug('[ "' . $event->getId() . '" ] Dispatched to ' . get_class($subscription->getSubscriber()));
                    $subscription->getSubscriber()->handle($event);
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
        }

        $timer->stop();
        $this->logger->notice('[ "' . $event->getId() . '" ] Dispatch done in ' . $timer->getElapsed() . ' s.');
    }
}
