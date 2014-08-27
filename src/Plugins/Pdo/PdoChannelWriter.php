<?php

namespace Aztech\Events\Bus\Plugins\Pdo;

use Aztech\Events\Event;
use Aztech\Events\Bus\Channel\ChannelWriter;

class PdoChannelWriter implements ChannelWriter
{

    private $pdo;

    private $helper;

    public function __construct(\PDO $connection, PdoHelper $helper)
    {
        $this->pdo = $connection;
        $this->helper = $helper;
    }

    public function write(Event $event, $serializedEvent)
    {
        $query = $this->helper->getWriteQuery();

        $statement = $this->pdo->prepare($query);
        $statement->execute(array(
            ':id' => $event->getId(),
            ':data' => $serializedEvent
        ));
    }
}
