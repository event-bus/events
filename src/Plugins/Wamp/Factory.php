<?php

namespace Aztech\Events\Bus\Plugins\Wamp;

use Aztech\Events\Bus\AbstractFactory;

class Factory extends AbstractFactory
{

    protected function createChannel(array $options)
    {
        $transport = new Channel();

        return $transport;
    }

    public function createPublisher(array $options = array())
    {
        $publisher = new Publisher($this->serializer);

        return $publisher;
    }

    public function createConsumer(array $options = array())
    {
        throw new \BadMethodCallException('Consuming not supported.');
    }
}
