<?php

namespace Aztech\Events\Plugins\Amqp;

use Aztech\Events\Event;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Transport implements \Aztech\Events\Transport
{

    /**
     *
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     *
     * @var string
     */
    private $readQueue;

    /**
     *
     * @var string
     */
    private $writeExchange;

    /**
     *
     * @var AMQPMessage
     */
    private $lastMessage = null;

    public function __construct(AMQPChannel $channel, $writeExchange, $readQueue)
    {
        $this->channel = $channel;
        $this->readQueue = $readQueue;
        $this->writeExchange = $writeExchange;
    }

    public function read()
    {
        $callback = $this->buildHandleMessageCallback($dispatcher);

        $this->channel->basic_consume($this->readQueue, '', false, false, false, false, $callback);
        $this->channel->wait();

        return $this->lastMessage->body;
    }

    public function handleMessage(AMQPMessage $message, $dispatcher)
    {
        if ($message->has('correlation_id')) {
            $correlationId = $message->get('correlation_id');
        }
        else {
            $key = $message->has('routing_key') ? $message->get('routing_key') : Uuid::uuid4();
            $correlationId = 'local-' . Uuid::uuid5(Uuid::uuid4(), $key);
        }

        $this->logger->debug('[ "' . $correlationId . '" ] Received payload : ' . $message->body);
        $this->lastMessage = $message;
        $this->doMessageAck($correlationId, $message);
    }

    private function buildHandleMessageCallback($dispatcher)
    {
        $self = $this;

        return function ($message) use($self, $dispatcher)
        {
            $self->handleMessage($message, $dispatcher);
        };
    }

    /**
     * @param AMQPMessage $message
     */
    private function doMessageAck($correlationId, $message)
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        $this->logger->info('[ "' . $correlationId . '" ] Acknowledged.');
    }

    public function write(Event $event, $serializedEvent)
    {
        $message = new AMQPMessage($serializedEvent, array(
            'delivery_mode' => 2,
            'correlation_id' => $event->getId()
        ));

        $category = $event->getCategory();

        $this->channel->basic_publish($message, $this->writeExchange, $category);
    }
}
