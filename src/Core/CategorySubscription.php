<?php

namespace Aztech\Events\Core;

use Aztech\Events\Util\TrieMatcher\Trie;
use Aztech\Events\Subscriber;

class CategorySubscription
{

    /**
     *
     * @var string
     */
    private $categoryFilter;

    /**
     *
     * @var \Aztech\Events\EventSubscriber
     */
    private $subscriber;

    /**
     *
     * @var \Aztech\Events\CategoryMatcher
     */
    private $matcher;

    public function __construct($categoryFilter, Subscriber $subscriber = null)
    {
        $this->categoryFilter = $categoryFilter;
        $this->subscriber = $subscriber;
        $this->matcher = new Trie($this->categoryFilter);
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    public function getCategoryFilter()
    {
        return $this->categoryFilter;
    }

    /**
     * @param string $category
     */
    public function matches($category)
    {
        return $this->matcher->matches($category);
    }
}
