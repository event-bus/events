<?php

namespace Aztech\Events\Bus\Plugins\Socket;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class SocketChannelProvider implements ChannelProvider
{

    public function createChannel(array $options = array())
    {
        switch($options['protocol']) {
            case 'ipc':
                return AF_UNIX;
            case 'ipv6':
                return AF_INET6;
            case 'ipv4':
            default:
                return AF_INET;
        }

        $socket = socket_create($domain, SOCK_STREAM);
        $socket = new SocketWrapper($socket);

        $reader = new SocketChannelReader($socket);
        $writer = new SocketChannelWriter($socket);

        return new ReadWriteChannel($reader, $writer);
    }
}
