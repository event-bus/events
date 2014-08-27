<?php

namespace Aztech\Events\Bus\Transport;

use Aztech\Events\Bus\Transport;

class LegacyTransportAdapter implements TransportProvider
{

    private $transport = null;

    private $canRead = true;

    private $canWrite = true;

    public function __construct(Transport $transport, $disableRead = false, $disableWrite = false)
    {
        $this->transport = $transport;
        $this->canRead = ! $disableRead;
        $this->canWrite = ! $disableWrite;
    }

    public function canRead()
    {
        return $this->canRead;
    }

    public function canWrite()
    {
        return $this->canWrite;
    }

    public function getReader()
    {
        return $this->transport;
    }

    public function getWriter()
    {
        return $this->transport;
    }
}
