<?php

namespace Aztech\Events\Bus\Plugins\Pdo;

use Aztech\Events\Bus\Channel\ChannelProvider;
use Aztech\Events\Bus\Channel\ReadWriteChannel;

class PdoChannelProvider implements ChannelProvider
{

    public function createChannel(array $options = array())
    {
        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s', $options['driver'], $options['host'], $options['port'], $options['database']);

        $connection = new \PDO($dsn, $options['user'], $options['pass'], $options);
        $helper = new PdoHelper();

        $helper->setPdoMetadata($options['table'], $options['id-column'], $options['data-column']);

        $reader = new PdoChannelReader($connection, $helper);
        $writer = new PdoChannelWriter($connection, $helper);

        return new ReadWriteChannel($reader, $writer);
    }
}
