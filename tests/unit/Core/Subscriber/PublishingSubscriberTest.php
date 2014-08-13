<?php

namespace Aztech\Events\Tests\Core\Subscriber;

use Aztech\Events\Tests\CategoryMatchTruthTable;
use Aztech\Events\Core\Subscriber\PublishingSubscriber;
use Aztech\Events\Core\Event;

class PublishingSubscriberTest extends \PHPUnit_Framework_TestCase
{

    private $publisher;

    protected function setUp()
    {
        $this->publisher = $this->getMock('\Aztech\Events\Publisher');
    }

    public function getConstraintTruthTable()
    {
        return CategoryMatchTruthTable::get();
    }

    /**
     * @dataProvider getConstraintTruthTable
     */
    public function testSupportsRespectsConstraintTruthTable($category, $filter, $expected)
    {
        $subscriber = new PublishingSubscriber($this->publisher, $filter);
        $event = new Event($category);

        $this->assertEquals($expected, $subscriber->supports($event));
    }

    public function testHandleForwardsEventToPublisher()
    {
        $event = new Event('category');

        $this->publisher->expects($this->once())
            ->method('publish')
            ->with($this->equalTo($event));

        $subscriber = new PublishingSubscriber($this->publisher, '#');
        $subscriber->handle($event);
    }
}
