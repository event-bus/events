<?php

namespace Evaneos\Events\Processors\RabbitMQ;

use Evaneos\Events\Event;
use Evaneos\Events\EventSerializer;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Evaneos\Events\EventSubscriber;
use Evaneos\Events\EventDispatcher;
use Evaneos\Events\EventProcessor;

class RabbitMQEventProcessor implements EventProcessor
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
     * @param \PhpAmqpLib\Channel\AMQPChannel $channel
     * @param string $queue
     * @param \Evaneos\Events\EventSerializer $serializer
     */
    public function __construct(AMQPChannel $channel, $queue, EventSerializer $serializer)
    {
        $this->channel = $channel;
        $this->queue = $queue;
        $this->serializer = $serializer;
    }

    public function processNext(EventDispatcher $dispatcher)
    {
        $serializer = $this->serializer;

        $callback = function($message) use ($dispatcher, $serializer, $channel) {
            $serializedEvent = $message->body;
            $event = $serializer->deserialize($serializedEvent);

            $dispatcher->dispatch($event);

            // Ack message
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
            // Unregister consumer (single message processing)
            $msg->delivery_info['channel']->basic_cancel($msg->delivery_info['consumer_tag']);
        };

        $this->channel->basic_consume($this->queue, '', false, false, false, false, $callback);

        while (! empty($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }


}
