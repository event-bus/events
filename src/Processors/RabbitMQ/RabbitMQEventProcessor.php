<?php

namespace Evaneos\Events\Processors\RabbitMQ;

use Evaneos\Events\Event;
use Evaneos\Events\EventSerializer;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Evaneos\Events\EventSubscriber;
use Evaneos\Events\EventDispatcher;
use Evaneos\Events\EventProcessor;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Evaneos\Events\Processors\AbstractProcessor;

class RabbitMQEventProcessor extends AbstractProcessor implements LoggerAwareInterface
{

    /**
     *
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     *
     * @var \Evaneos\Events\EventSerializer
     */
    private $serializer;

    /**
     *
     * @var string
     */
    private $queue;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param string $queue
     * @param \Evaneos\Events\EventSerializer $serializer
     */
    public function __construct(AMQPChannel $channel, $queue, EventSerializer $serializer)
    {
        parent::__construct();

        $this->channel = $channel;
        $this->queue = $queue;
        $this->serializer = $serializer;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function processNext(EventDispatcher $dispatcher)
    {
        $self = $this;
        $serializer = $this->serializer;
        $channel = $this->channel;
        $logger = $this->logger;

        $callback = $this->buildHandleMessageCallback($dispatcher);

        $this->channel->basic_consume($this->queue, '', false, false, false, false, $callback);
        $this->channel->wait();
    }

    private function buildHandleMessageCallback($dispatcher)
    {
        $self = $this;

        return function ($message) use($self, $dispatcher)
        {
            $self->handleMessage($message, $dispatcher);
        };
    }

    public function handleMessage(AMQPMessage $message, $dispatcher)
    {
        try {
            if ($message->has('correlation_id')) {
                $this->logger->debug('Handling message with correlation id "' . $message->get('correlation_id') . '"');
            }

            $serializedEvent = $message->body;
            $event = $this->serializer->deserialize($serializedEvent);

            $this->onProcessing($event);

            $this->logger->info('Processing event : ' . $event->getCategory());
            $this->logger->debug('Event data : ' . $serializedEvent);

            $dispatcher->dispatch($event);

            // Unregister (process only one message at a time)
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        }
        catch (\Exception $ex) {
            $this->onError($event, $ex);
        }

        $this->onProcessed($event);
    }
}
