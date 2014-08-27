<?php

namespace Aztech\Events\Bus\Processor;

use Aztech\Events\Bus\AbstractProcessor;
use Aztech\Events\Bus\Serializer;
use Aztech\Events\Dispatcher;
use Aztech\Events\Bus\Transport\Reader;

class TransportProcessor extends AbstractProcessor
{

    private $reader;

    private $serializer;

    public function __construct(Reader $reader, Serializer $serializer)
    {
        parent::__construct();

        $this->serializer = $serializer;
        $this->reader = $reader;
    }

    public function processNext(Dispatcher $dispatcher)
    {
        $this->logger->debug('Getting next available event from transport.');

        $serializedObject = $this->reader->read();
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
