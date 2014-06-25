<?php

namespace Evaneos\Events;

class StandardDispatcher implements EventDispatcher
{
    /**
     *
     * @var CategorySubscription[]
     */
    private $subscriptions = array();

    public function addListener($category, EventSubscriber $subscriber)
    {
        $this->subscriptions[] = new CategorySubscription($category, $subscriber);
    }

    public function dispatch(Event $event)
    {
        $category = $event->getCategory();

        foreach ($this->subscriptions as $subscription) {
            $hasMatch = $subscription->matches($category);

            if ($hasMatch && $subscription->getSubscriber()->supports($event)) {
                $subscription->getSubscriber()->handle($event);
            }
        }
    }
}
