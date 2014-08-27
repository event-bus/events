<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Bus\Channel;

class ReadOnlyChannel implements Channel
{

    private $reader;

    public function __construct(ChannelReader $reader)
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
