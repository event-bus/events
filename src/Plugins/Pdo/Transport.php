<?php

namespace Aztech\Events\Plugins\PDO;

use Aztech\Events\Event;

class Transport implements \Aztech\Events\Transport
{

    private $pdo;

    private $helper;

    public function __construct(\PDO $connection)
    {
        $this->pdo = $connection;
        $this->helper = new Helper();
    }

    public function setPdoMetadata($tableName, $idColumn, $dataColumn)
    {
        return $this->helper->setPdoMetadata($tableName, $idColumn, $dataColumn);
    }

    public function read()
    {
        $query = $this->helper->getReadQuery();

        if (! $this->pdo->beginTransaction()) {
            throw new \RuntimeException('Unable to start a database transaction.');
        }

        $statement = $this->pdo->query($this->helper->getReadQuery());
        if (! ($result = $statement->execute())) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Unable to query database.');
        }

        $data = $statement->fetch();
        if (! $data) {
            $this->pdo->rollBack();
            return false;
        }

        $id = $data['id'];
        $event = $data['data'];

        $statement = $this->pdo->exec($this->helper->getDeleteQuery(), array('id' => $id));
        if ($statement == 0) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Unable to delete event record.');
        }
        elseif ($statement > 1) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Tried to delete more than one event.');
        }

        $this->pdo->commit();

        return $data;
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
