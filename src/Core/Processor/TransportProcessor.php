<?php

namespace Aztech\Events\Core\Processor;

use Aztech\Events\Core\AbstractProcessor;
use Aztech\Events\Transport;
use Aztech\Events\Serializer;
use Aztech\Events\Dispatcher;

class TransportProcessor extends AbstractProcessor
{

    private $transport;

    private $serializer;

    public function __construct(Transport $transport, Serializer $serializer)
    {
        parent::__construct();

        $this->serializer = $serializer;
        $this->transport = $transport;
    }

    public function processNext(Dispatcher $dispatcher)
    {
        $this->logger->debug('Getting next available event from transport.');

        $serializedObject = $this->transport->read();
        $event = $this->serializer->deserialize($serializedObject);

        if ($event) {
            $this->logger->debug('Dispatching read event with category "' . $event->getCategory() . '".');

            $this->onProcessing($event);
            $dispatcher->dispatch($event);
            $this->onProcessed($event);
        }
        else {
            $this->logger->debug('Transport had no available events');
        }
    }
}
