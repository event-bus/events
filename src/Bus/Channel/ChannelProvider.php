<?php

namespace Aztech\Events\Bus\Channel;

use Aztech\Events\Bus\Channel;

interface ChannelProvider
{

    /**
     *
     * @param array $options
     * @return Channel
     */
    function createChannel(array $options = array());
}
