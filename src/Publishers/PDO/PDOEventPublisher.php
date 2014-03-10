<?php

namespace Evaneos\Events\Publishers\PDO;

use Evaneos\Events\EventPublisher;
use Evaneos\Events\Event;
use Evaneos\Events\EventSerializer;

class PDOEventPublisher implements EventPublisher
{

    private $pdo;

    private $eventSerializer;

    private $eventTable = 'events';

    private $categoryColumn = 'category';

    private $dataColumn = 'data';

    public function __construct(\PDO $connection, EventSerializer $serializer)
    {
        $this->pdo = $connection;
        $this->eventSerializer = $serializer;
    }

    public function setPdoMetadata($tableName, $categoryColumn, $dataColumn)
    {
        $this->eventTable = $tableName;
        $this->categoryColumn = $categoryColumn;
        $this->dataColumn = $dataColumn;
    }

    public function publish(Event $event)
    {
        $query = $this->getQuery();

        $data = $this->eventSerializer->serialize($event);
        $category = $event->getCategory();

        $statement = $this->pdo->prepare($query);
        $statement->execute(array(':category' => $category, ':data' => $data));
    }

    private function getQuery()
    {
        return sprintf('INSERT INTO %s (%s, %s) VALUES (:category, :data)', $this->eventTable, $this->categoryColumn,
            $this->dataColumn);
    }
}