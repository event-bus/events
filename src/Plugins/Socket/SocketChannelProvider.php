<?php

namespace Aztech\Events\Bus\Plugins\Socket;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class SocketChannelProvider implements ChannelProvider
{

    public function createChannel(array $options = array())
    {
        $socketBuilder = new SocketWrapperBuilder();
        $socket = $socketBuilder->build($options);

        $reader = new SocketChannelReader($socket);
        $writer = new SocketChannelWriter($socket);

        return new ReadWriteChannel($reader, $writer);
    }
}
