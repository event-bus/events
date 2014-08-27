<?php

namespace Aztech\Events\Bus\Plugins\Pdo;

class PdoHelper
{

    private $eventTable = 'events';

    private $idColumn = 'id';

    private $dataColumn = 'data';

    public function setPdoMetadata($tableName, $idColumn, $dataColumn)
    {
        if (empty($tableName) || ! is_string($tableName)) {
            throw new \InvalidArgumentException('Table name must be a non-empty string.');
        }
        elseif (empty($idColumn) || ! is_string($idColumn)) {
            throw new \InvalidArgumentException('Id column must be a non-empty string.');
        }
        elseif (empty($dataColumn) || ! is_string($dataColumn)) {
            throw new \InvalidArgumentException('Data column must be a non-empty string.');
        }

        $this->eventTable = $tableName;
        $this->idColumn = $idColumn;
        $this->dataColumn = $dataColumn;
    }

    public function getWriteQuery()
    {
        return sprintf('INSERT INTO %s (%s, %s) VALUES (:id, :data)', $this->eventTable, $this->idColumn, $this->dataColumn);
    }

    public function getReadQuery()
    {
        return sprintf('SELECT %s AS id, %s AS data FROM %s', $this->idColumn, $this->dataColumn, $this->eventTable);
    }

    public function getDeleteQuery()
    {
        return sprintf('DELETE FROM %s WHERE %s = :id', $this->eventTable, $this->idColumn);
    }
}
