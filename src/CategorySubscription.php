<?php

namespace Evaneos\Events;

class CategorySubscription
{
    /**
     *
     * @var string
     */
    private $categoryFilter;

    /**
     *
     * @var \Evaneos\Events\EventSubscriber
     */
    private $subscriber;

    public function __construct($categoryFilter, EventSubscriber $subscriber)
    {
        $this->categoryFilter = $categoryFilter;
        $this->subscriber = $subscriber;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function getCategoryFilter()
    {
        return $this->categoryFilter;
    }

    public function matches($category)
    {
        $matcher = new CategoryMatcher();

        return $matcher->checkMatch($this->categoryFilter, $category);
    }
}
