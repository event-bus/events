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
        $this->helper = $pdoHelper;
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
}
