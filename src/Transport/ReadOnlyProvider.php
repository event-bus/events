<?php

namespace Aztech\Events\Transport;

class ReadOnlyProvider implements TransportProvider
{

    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
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
        return false;
    }

    function getWriter()
    {
        throw new \BadMethodCallException('Write operations are not supported.');
    }
}
