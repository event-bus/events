<?php

namespace Aztech\Events\Tests\Core\Subscriber;

use Aztech\Events\Core\Subscriber\CallbackSubscriber;
use Aztech\Events\Event;
class CallbackSubscriberTest extends \PHPUnit_Framework_TestCase
{

    public static function dummyStaticCallable()
    {

    }

    public function dummyInstanceCallable()
    {

    }

    public function getInvalidCtorArgs()
    {
        return array(
            array(0), array(false), array('SomeFunctionNameLikelyNeverEverEverEverTakenOrFuckMeThisIsInsane'),
            array(array(null, null)), array(new \stdClass())
        );
    }

    /**
     * @dataProvider getInvalidCtorArgs
     * @expectedException \InvalidArgumentException
     */
    public function testCannotCreateWithNonCallableValue($value)
    {
        $subscriber = new CallbackSubscriber($value);
    }

    public function getCallables()
    {
        return array(
            array(array(__CLASS__, 'dummyStaticCallable')),
            array(array($this, 'dummyInstanceCallable')),
            array(function () {

            })
        );
    }

    /**
     * @dataProvider getCallables
     * @param unknown $callable
     */
    public function testCanInitWithCallables($callable)
    {
        $subscriber = new CallbackSubscriber($callable);

        $this->assertNotNull($subscriber);
    }

    public function testSupportsReturnsTrueForAnyEvent()
    {
        $subscriber = new CallbackSubscriber(function () { });

        $this->assertTrue($subscriber->supports($this->getMock('\Aztech\Events\Event')));
    }

    public function testCallbackIsInvoked()
    {
        $count = 0;
        $original = $this->getMock('\Aztech\Events\Event');
        $self = $this;

        $callback = function(Event $event) use (& $original, & $count, $self) {
            $count++;
            $self->assertSame($original, $event);
        };

        $subscriber = new CallbackSubscriber($callback);

        $subscriber->handle($original);

        $this->assertGreaterThan(0, $count);
    }

}
