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

    /**
     *
     * @var \Evaneos\Events\CategoryMatcher
     */
    private $matcher;

    public function __construct($categoryFilter, EventSubscriber $subscriber)
    {
        $this->categoryFilter = $categoryFilter;
        $this->subscriber = $subscriber;
        $this->matcher = new CategoryMatcher();
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
        return $this->matcher->checkMatch($this->categoryFilter, $category);
    }
}
