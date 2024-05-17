<?php

declare(strict_types=1);

namespace Drupal\currency\Model\Crud;

use Drupal;
use Drupal\Core\Database\Connection;
use Exception;
use PDO;

class Base
{
    protected string $table = '';
    protected string $idField = '';

    public function __construct(
        protected Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function get(string $id): array
    {
        $query = $this->connection->select($this->table, 't');
        $query->condition($this->idField, $id);
        $query->range(0, 1);

        return $query->execute()->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function getList(array $conditions): array
    {
        $query = $this->connection->select($this->table, 't')->fields('t');
        $groupCondition = $query->andConditionGroup();

        foreach ($conditions as $condition) {
            $groupCondition->condition(
                $condition['field'],
                $condition['value'],
                $condition['operator'] ?? '='
            );
        }

        $query->condition($groupCondition);

        return $query->execute()->fetchAllAssoc($this->idField, PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function getAll(): array
    {
        $query = $this->connection->select($this->table, 't');
        $query->fields('t');
        $query->distinct();

        return $query->execute()->fetchAllAssoc($this->idField, PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function save(array $data): void
    {
        if (empty($data)) {
            return;
        }

        $upsert = $this->connection
            ->upsert($this->table)
            ->key($this->idField);
        $upsert->fields(array_keys($data[0]));

        foreach ($data as $value) {
            $upsert->values($value);
        }

        $upsert->execute();
    }

    /**
     * @throws Exception
     */
    public function delete(string $id): void
    {
        $query = Drupal::database()->delete($this->table);
        $query->condition($this->idField, $id);
        $query->execute();
    }
}
