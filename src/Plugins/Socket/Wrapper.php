<?php

namespace Aztech\Events\Bus\Plugins\Socket;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

class Wrapper
{

    protected $logger;

    protected $socket;

    public function __construct($socket, LoggerInterface $logger = null)
    {
        if (! $this->isSocket($socket)) {
            throw new \InvalidArgumentException('$socket must a socket ressource.');
        }

        $this->socket = $socket;
        $this->logger = $logger ?: new NullLogger();
    }

    private function isSocket($socket)
    {
        if (! is_resource($socket)) {
            return false;
        }

        return get_resource_type($socket) == 'Socket';
    }

    private $previousError = 0;

    protected function dumpPotentialError($context = '')
    {
        $error = socket_last_error($this->socket);

        if ($error !== 0 && $error !== 10035) {
            $message = '';

            if (! empty($context)) {
                $message .= $context . ' :: ';
            }

            $message .= 'Socket error : ' . socket_strerror($error) . PHP_EOL;

            $this->logger->warning($message);
        }

        socket_clear_error($this->socket);
    }

    public function readRaw()
    {
        $received = "";
        $readLen = 1;

        do {
            $data = socket_read($this->socket, 1);
            $received .= $data;

            $this->dumpPotentialError(__METHOD__);
        }
        while (strlen($data) > 0);

        if (! empty($received)) {
            $this->logger->debug($this->getPeerName() . ': Received data');
            $this->logger->debug(' > ' . implode('\n >', explode("\r\n", trim($received))));
        }

        return $received;
    }

    public function writeRaw($data)
    {
        $writtenCharCount = 0;
        $writtenChars = '';

        while ($writtenCharCount !== false && $writtenCharCount < strlen($data)) {
            $data = substr($data, $writtenCharCount);
            $writtenCharCount = socket_write($this->socket, $data);
            $writtenChars .= substr($data, 0, $writtenCharCount);

            $this->dumpPotentialError(__METHOD__);
        }

        $this->logger->debug($this->getPeerName() . ': Sent data');
        $this->logger->debug(' > ' . implode('\n >', explode("\r\n", trim($writtenChars))));
    }

    protected function getPeerName()
    {
        $address = '';
        $port = 0;

        socket_getpeername($this->socket, $address, $port);

        return sprintf('%s:%s', $address, $port);
    }
}
