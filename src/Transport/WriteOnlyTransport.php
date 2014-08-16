<?php

namespace Aztech\Events\Transport;

class WriteOnlyTransport implements TransportProvider
{
    
    private $writer;
    
    public function __construct(Writer $writer)
    {
        $this->writer = $writer;
    }
    
    function canRead()
    {
        return false;
    }
    
    function getReader()
    {
        throw new \BadMethodCallException('Read operations are not supported.');
    }
    
    function canWrite()
    {
        return true;
    }
    
    function getWriter()
    {
        return $this->writer;
    }
}