<?php

namespace Aztech\Events\Bus\Plugins\Socket;

class SocketWrapperBuilder
{

    public function build(array $options)
    {
        $domain = $this->getDomain($protocol);
        $protocol = $this->getProtocol($protocol);

        $socket = socket_create($domain, SOCK_STREAM, $protocol);

        return new SocketWrapper($socket);
    }

    public function getDomain($protocol)
    {
        switch ($protocol) {
            case 'ipc':
                return AF_UNIX;
            case 'ipv6':
                return AF_INET6;
            case 'ipv4':
            default:
                return AF_INET;
        }
    }

    public function getProtocol($protocol)
    {
        switch ($protocol) {
            case 'ipc':
                return SOL_SOCKET;
            case 'ipv6':
            case 'ipv4':
            default:
                return SOL_TCP;
        }
    }
}
