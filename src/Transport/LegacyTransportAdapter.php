<?php

namespace Aztech\Events\Transport;

use Aztech\Events\Transport;

class LegacyTransportAdapter implements TransportProvider
{

    private $transport;

    private $canRead = true;
    
    private $canWrite = true;
    
    public function __construct(Transport $transport, $disableRead = false, $disableWrite = false)
    {
        $this->transport;
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
        return $this->writer;
    }
}