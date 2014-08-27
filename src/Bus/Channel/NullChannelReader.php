<?php

namespace Aztech\Events\Bus\Channel;

class NullChannelReader implements ChannelReader
{

    function read()
    {
        return null;
    }
}
