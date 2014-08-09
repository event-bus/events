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

class RabbitMQEventProcessor implements EventProcessor, LoggerAwareInterface
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
        $serializer = $this->serializer;
        $channel = $this->channel;
        $logger = $this->logger;

        $callback = function($message) use ($dispatcher, $serializer, $channel, $logger) {

            $serializedEvent = $message->body;
            $event = $serializer->deserialize($serializedEvent);

            $this->logger->info('Processing event : ' . $event->getCategory());
            $this->logger->debug('Event data : ' . $serializedEvent);

            $dispatcher->dispatch($event);

            // Unregister (process only one message at a time)
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

        $this->channel->basic_consume($this->queue, '', false, false, false, false, $callback);
        $this->channel->wait();
    }
}
