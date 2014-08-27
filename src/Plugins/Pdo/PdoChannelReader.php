<?php

namespace Aztech\Events\Bus\Plugins\Pdo;

use Aztech\Events\Bus\Channel\ChannelReader;

class PdoChannelReader implements ChannelReader
{

    private $pdo;

    private $helper;

    public function __construct(\PDO $connection, PdoHelper $helper)
    {
        $this->pdo = $connection;
        $this->helper = $helper;
    }

    public function read()
    {
        $this->beginTransaction();

        if (! ($data = $this->getNextRecordData())) {
            return false;
        }

        $this->deleteRecord($data['id']);
        $this->pdo->commit();

        return $data['data'];
    }

    private function beginTransaction()
    {
        if (! $this->pdo->beginTransaction()) {
            throw new \RuntimeException('Unable to start a database transaction.');
        }
    }

    private function getNextRecordData()
    {
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

        return $data;
    }

    private function deleteRecord($id)
    {
        $statement = $this->pdo->prepare($this->helper->getDeleteQuery());

        if (! $statement->execute(array('id' => $id))) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Unable to delete event record.');
        }
        elseif ($statement->rowCount() > 1) {
            $this->pdo->rollBack();
            throw new \RuntimeException('Tried to delete more than one event.');
        }
    }
}
