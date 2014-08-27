<?php

namespace Aztech\Events\Bus\Plugins\Memory;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class MemoryChannelProvider implements ChannelProvider
{

    public function createChannel(array $options = array())
    {
        $memory = new MemoryChannelReaderWriter();

        return new ReadWriteChannel($memory, $memory);
    }
}
