<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Bus\Channel;

class WriteOnlyChannel implements Channel
{

    private $writer;

    public function __construct(ChannelWriter $writer)
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
