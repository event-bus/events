<?php

namespace Evaneos\Events\Factory;

class StompFactory implements Factory
{

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
        throw new \BadMethodCallException();
    }

    public function createDispatcher(array $options = array())
    {
        $dispatcher = new SimpleDispatcher();
        $publisher = new StompEventPublisher($this->serializer);

        $dispatcher->addListener('*', new PublishingSubscriber($publisher));

        return $dispatcher;
    }
}
