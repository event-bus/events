<?php

namespace Aztech\Events\Bus\Plugins\Redis;

use Aztech\Events\Event;
use Aztech\Events\Bus\Channel\ChannelWriter;
use Predis\Client;

class RedisChannelWriter implements ChannelWriter
{

    private $client;

    private $key = null;

    private $processingKey = null;

    public function __construct(Client $redisClient, $eventKey, $processingKey)
    {
        if (empty($eventKey)) {
            throw new \InvalidArgumentException('Event key must be provided.');
        }

        if (empty($processingKey)) {
            throw new \InvalidArgumentException('Processing key must be provided.');
        }

        $this->client = $redisClient;
        $this->key = $eventKey;
        $this->processingKey = $processingKey;
    }

    public function write(Event $event, $serializedRepresentation)
    {
        $this->client->rpush($this->key, $serializedRepresentation);
    }
}
