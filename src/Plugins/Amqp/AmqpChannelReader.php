<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use Aztech\Events\Bus\Channel\ChannelReader;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Rhumsaa\Uuid\Uuid;

class AmqpChannelReader implements ChannelReader, LoggerAwareInterface
{

    private $channel;

    private $exchange;

    private $readQueue;

    private $lastMessage;

    private $logger;

    private $initialized = false;

    private $categoryHelper;

    public function __construct(AMQPChannel $channel, $exchange, $readQueue)
    {
        $this->channel = $channel;
        $this->exchange = $exchange;
        $this->readQueue = $readQueue;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function read()
    {
        $callback = array($this, 'handleMessage');

        $this->channel->basic_consume($this->readQueue, '', false, false, false, false, $callback);
        $this->channel->wait();

        return $this->lastMessage->body;
    }

    public function handleMessage(AMQPMessage $message)
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

    /**
     * @param AMQPMessage $message
     */
    private function doMessageAck($correlationId, $message)
    {
        $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);

        $this->logger->info('[ "' . $correlationId . '" ] Acknowledged.');
    }
}
