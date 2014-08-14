<?php

namespace Aztech\Events\Plugins\ZeroMq;

class SocketWrapper
{

    private $boundOrConnected = false;

    private $host;

    private $port;

    private $scheme;

    private $socket;

    private $forceBind = false;

    public function __construct(\ZMQSocket $socket, $dsn, $forceBind = false)
    {
        $this->dsn = $dsn;
        $this->socket = $socket;
        $this->forceBind = $forceBind;
    }

    public function __destruct()
    {
        if ($this->boundOrConnected) {
            $this->socket->unbind($this->dsn);
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->socket, $method), $args);
    }

    public function getSocket()
    {
        return $this->socket;
    }

    public function connectIfNecessary()
    {
        if ($this->forceBind) {
            return $this->bindIfNecessary();
        }

        if (! $this->boundOrConnected) {
            echo 'Connecting to DSN ' . $this->dsn . PHP_EOL;

            $this->socket->connect($this->dsn);

            echo 'Connected' . PHP_EOL;

            $this->boundOrConnected = true;
        }
    }

    private function bindIfNecessary()
    {
        if (! $this->boundOrConnected) {
            echo 'Binding to DSN ' . $this->dsn . PHP_EOL;

            $this->socket->bind($this->dsn);

            echo 'Connected' . PHP_EOL;

            $this->boundOrConnected = true;
        }
    }
}
