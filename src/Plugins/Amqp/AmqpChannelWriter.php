<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use Aztech\Events\Bus\Channel\ChannelWriter;
use Aztech\Events\Event;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AmqpChannelWriter implements ChannelWriter, LoggerAwareInterface
{

    /**
     *
     * @var AMQPChannel
     */
    private $channel;

    /**
     *
     * @var string
     */
    private $writeExchange;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var CategoryPrefixHelper
     */
    private $categoryHelper = null;

    public function __construct(AMQPChannel $channel, $writeExchange, $eventPrefix = '')
    {
        $this->channel = $channel;
        $this->writeExchange = $writeExchange;
        $this->logger = new NullLogger();
        $this->categoryHelper = new CategoryPrefixHelper($eventPrefix);
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function write(Event $event, $serializedEvent)
    {
        $message = new AMQPMessage($serializedEvent, array(
            'delivery_mode' => 2,
            'correlation_id' => $event->getId()
        ));

        $category = $this->categoryHelper->getPrefixedCategory($event->getCategory());

        $this->channel->basic_publish($message, $this->writeExchange, $category);
    }
}
