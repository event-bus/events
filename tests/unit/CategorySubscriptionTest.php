<?php

namespace Aztech\Events\Tests;

use Aztech\Events\Core\CategorySubscription;
class CategorySubscriptionTest extends \PHPUnit_Framework_TestCase
{

    public function testParametersAreCorrectlyAssignedWithDefaultValues()
    {
        $categoryFilter = '*';
        $subscription = new CategorySubscription($categoryFilter);

        $this->assertEquals($categoryFilter, $subscription->getCategoryFilter());
        $this->assertNull($subscription->getSubscriber());
    }

    public function testParametersAreCorrectlyAssigned()
    {
        $categoryFilter = '*';
        $subscriber = $this->getMock('\Aztech\Events\Subscriber');
        $subscription = new CategorySubscription($categoryFilter, $subscriber);

        $this->assertEquals($categoryFilter, $subscription->getCategoryFilter());
        $this->assertSame($subscriber, $subscription->getSubscriber());
    }

    public function getTruthTable()
    {
        return CategoryMatchTruthTable::get();
    }


    /**
     *
     * @dataProvider getTruthTable
     */
    public function testCategoryAreCorrectlyMatched($category, $filter, $expected)
    {
        $subscriber = $this->getMock('\Aztech\Events\Subscriber');
        $subscription = new CategorySubscription($filter, $subscriber);

        $this->assertEquals($expected, $subscription->matches($category));
    }
}
