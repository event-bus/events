<?php

namespace Aztech\Events\Bus\Transport;

use Aztech\Events\Bus\Transport;

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
