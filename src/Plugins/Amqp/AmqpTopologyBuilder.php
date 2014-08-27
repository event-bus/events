<?php

namespace Aztech\Events\Bus\Plugins\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;

class AmqpTopologyBuilder
{

    private $channel;

    private $exchange;

    private $categoryHelper;

    private $queueName;

    private $built = false;

    public function __construct(AMQPChannel $channel, $exchange, $queue, $prefix = '')
    {
        $this->channel = $channel;
        $this->exchange = $exchange;
        $this->queueName = $queue;
        $this->categoryHelper = new CategoryPrefixHelper($prefix);
    }

    public function build()
    {
        if (! $this->built) {
            $this->channel->exchange_declare($this->exchange, 'topic', false, true, false);
            $this->channel->queue_declare($this->queueName, false, true, false, false);

            $routingKey = $this->categoryHelper->getPrefixedCategory('#');
            $this->channel->queue_bind($this->queueName, $this->exchange, $routingKey);

            $this->built = true;
        }
    }
}
