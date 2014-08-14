<?php

namespace Aztech\Events\Plugins\ZeroMq;

class SocketWrapper
{

    private $boundOrConnected = false;

    private $host;

    private $port;

    private $scheme;

    private $socket;

    public function __construct(\ZMQSocket $socket, $scheme = 'tcp', $host = 'localhost', $port = 5555)
    {
        $this->host = $host;
        $this->port = $port;
        $this->scheme = $scheme;
        $this->socket = $socket;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->socket, $method), $args);
    }

    public function connectIfNecessary()
    {
        if (! $this->boundOrConnected) {
            $dsn = sprintf('%s://%s:%s', $this->scheme, $this->host, $this->port);
            $this->socket->connect($dsn);

            $this->boundOrConnected = true;
        }
    }

    public function bindIfNecessart()
    {
        if (! $this->boundOrConnected) {
            $dsn = sprintf('%s://%s:%s', $this->scheme, $this->host, $this->port);
            $this->socket->connect($dsn);

            $this->boundOrConnected = true;
        }
    }
}
