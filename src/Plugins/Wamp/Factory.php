<?php

namespace Aztech\Events\Plugins\Wamp;

use Aztech\Events\Core\AbstractFactory;

class Factory extends AbstractFactory
{

    protected function createTransport(array $options)
    {
        $transport = new Transport();

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
