<?php

namespace Aztech\Events\Bus\Plugins\Pdo;

use Aztech\Events\Bus\AbstractFactory;
use Aztech\Events\Bus\Plugins\PDO\Channel;

class Factory extends AbstractFactory
{

    protected function createChannel(array $options)
    {
        $this->validateOptions($options);

        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s', $options['driver'], $options['host'], $options['port'], $options['database']);
        $connection = new \PDO($dsn, $options['user'], $options['pass'], $options);

        $transport = new Channel($connection);
        $transport->setPdoMetadata($options['table'], $options['id-column'], $options['data-column']);

        return $transport;
    }

    protected function getOptionKeys()
    {
        return array(
            'driver',
            'host',
            'port',
            'user',
            'pass',
            'database',
            'table',
            'id-column',
            'data-column'
        );
    }

    protected function getOptionDefaults()
    {
        return array(
            'driver'  => 'mysql',
            'port' => 3306,
            'host' => '127.0.0.1',
            'table' => 'events',
            'id-column' => 'id',
            'data-column' => 'data'
        );
    }
}
