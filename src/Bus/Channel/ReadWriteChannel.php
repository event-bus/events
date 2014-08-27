<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Bus\Channel;

class ReadWriteChannel implements Channel
{

    private $reader;

    private $writer;

    public function __construct(ChannelReader $reader, ChannelWriter $writer)
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
