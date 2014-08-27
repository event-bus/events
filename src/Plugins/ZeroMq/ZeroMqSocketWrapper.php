<?php

namespace Aztech\Events\Bus\Plugins\ZeroMq;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * ZMQSocket wrapper class (poor man's proxy)
 * @author thibaud
 * @method void send(string $data)
 * @method string recv()
 * @method void setSockOpt(mixed $option, mixed $value)
 */
class ZeroMqSocketWrapper implements LoggerAwareInterface
{

    /**
     *
     * @var bool true when a connection/binding has been set.
     */
    private $boundOrConnected = false;

    /**
     *
     * @var string
     */
    private $dsn;

    /**
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @var \ZMQSocket
     */
    private $socket;

    /**
     * Initialize a new wrapper using an unconnected socket.
     *
     * @param \ZMQSocket $socket
     * @param string $dsn
     */
    public function __construct(\ZMQSocket $socket, $dsn, LoggerInterface $logger = null)
    {
        $this->dsn = $dsn;
        $this->socket = $socket;
        $this->logger = $logger ?: new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Forward unknown calls to the underlying socket.
     * @param string $method
     * @param array $args
     */
    public function __call($method, $args)
    {
        if (! in_array(substr($method, 0, 4), [ 'recv', 'send' ])) {
            $this->logger->debug('__call forwarding to \ZMQSocket::' . $method . '().', [ 'args' => $args]);
        }

        // Poor man's proxy, but pointless to do more.
        return call_user_func_array(array($this->socket, $method), $args);
    }

    /**
     * Gets the underlying \ZMQSocket instance.
     * @return \ZMQSocket
     */
    public function getSocket()
    {
        return $this->socket;
    }


    /**
     * Connects the wrapped socket to the construction time supplied DSN.
     * @return void
     */
    public function connectIfNecessary($clientDelay = 1000000)
    {
        if (! $this->boundOrConnected) {
            $this->logger->debug('Connecting to "' . $this->dsn . '"...');

            $this->socket->connect($this->dsn);
            $this->boundOrConnected = true;

            // Give clients some time to start consuming, lazy shmucks.
            if ($clientDelay > 0) {
                usleep($clientDelay);
            }

            $this->logger->debug('Succesfully connected to "' . $this->dsn . '".');
        }
    }

    /**
     * Binds the wrapped socket to the construction time supplied DSN.
     * @return void
     */
    public function bindIfNecessary($clientDelay = 1000000)
    {
        if (! $this->boundOrConnected) {
            $this->logger->debug('Binding to "' . $this->dsn . '"...');

            $this->socket->bind($this->dsn);
            $this->boundOrConnected = true;

            // Give clients some time to connect, lazy shmucks.
            if ($clientDelay > 0) {
                usleep($clientDelay);
            }

            $this->logger->debug('Succesfully bound to "' . $this->dsn . '".');
        }
    }
}
