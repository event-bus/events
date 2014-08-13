<?php

namespace Aztech\Events\Plugins\Redis;

use Aztech\Events\Event;
use Predis\Client;

class Transport implements \Aztech\Events\Transport
{

    private $redis;

    private $key = null;

    private $processingKey = null;

    public function __construct(Client $redisClient, $eventKey, $processingKey = null)
    {
        if (empty($eventKey)) {
            throw new \InvalidArgumentException('Event key must be provided.');
        }

        $this->client = $redisClient;
        $this->key = $eventKey;
        $this->processingKey = $processingKey;
    }

    public function read()
    {
        if (! empty($this->processingKey)) {
            return $this->client->brpoplpush($this->key, $this->processingKey, 0);
        }
        else {
            return $this->client->brpop(array($this->key), 0);
        }
    }

    public function write(Event $event, $serializedRepresentation)
    {
        $this->client->rpush($this->key, $serializedRepresentation);
    }

}
