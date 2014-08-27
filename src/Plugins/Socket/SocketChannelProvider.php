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
                $domain = AF_UNIX;
                $protocol = SOL_SOCKET;
                break;
            case 'ipv6':
                $domain = AF_INET6;
                $protocol = SOL_TCP;
                break;
            case 'ipv4':
            default:
                $domain = AF_INET;
                $protocol = SOL_TCP;
                break;
        }

        $socket = socket_create($domain, SOCK_STREAM, $protocol);
        $socket = new SocketWrapper($socket);

        $reader = new SocketChannelReader($socket);
        $writer = new SocketChannelWriter($socket);

        return new ReadWriteChannel($reader, $writer);
    }
}
