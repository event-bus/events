<?php

namespace Aztech\Events\Factory;

use Aztech\Events\Publishers\Wamp\EventPublisher;
use Aztech\Events\Serializer;

class WampFactory implements Factory
{

    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createConsumer(array $options = array())
    {
        throw new \BadMethodCallException();
    }

    public function createProcessor(array $options = array())
    {
        throw new \BadMethodCallException();
    }

    public function createPublisher(array $options = array())
    {
        return new \Aztech\Events\Providers\Wamp\EventPublisher($this->serializer);
    }

    public function createDispatcher(array $options = array())
    {
        $dispatcher = new SimpleDispatcher();
        $publisher = new EventPublisher($this->serializer);

        $dispatcher->addListener('*', new PublishingSubscriber($publisher));

        return $dispatcher;
    }
}
