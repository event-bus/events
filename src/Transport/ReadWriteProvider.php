<?php

namespace Aztech\Events\Transport;

use Aztech\Events\Transport;

class ReadWriteProvider implements TransportProvider
{
    
    private $reader;
    
    private $writer;
    
    public function __construct(Reader $reader, Writer $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }
    
    function canRead()
    {
        return true;
    }
    
    function getReader()
    {
        return $this->reader;
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
