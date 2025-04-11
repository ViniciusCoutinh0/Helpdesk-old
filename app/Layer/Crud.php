<?php

namespace App\Layer;

use App\Layer\Instance\Db;
use App\Layer\Exception\DbException;

trait Crud
{
    /**
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        try {
            $column = implode(',', array_keys($data));
            $value  = ':' . implode(',:', array_keys($data));

            $create = Db::getInstance()->prepare("INSERT INTO {$this->table} ({$column}) VALUES ({$value})", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);

            foreach ($data as $column => $value) {
                $create->bindValue(":{$column}", $value);
            }

            $create->execute();
            return (int) Db::getInstance()->lastInsertId();
        } catch (\PDOException $exception) {
            throw new DbException($exception);
        }
    }

    /**
     * @param array $data
     * @param mixed $identify
     * @return int
     */
    public function update(array $data, $identify): int
    {
        try {
            if (count($data)) {
                $binds = [];
                $values = [];

                foreach ($data as $column => $value) {
                    $binds[] = "{$column} = :{$column}";
                    $values[] = $value;
                }

                $toStr = implode(", ", $binds);
                $update = Db::getInstance()->prepare("UPDATE {$this->table} SET {$toStr} WHERE {$identify}", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
                $update->execute($values);

                return ($update->rowCount() ?? 1);
            }
        } catch (\PDOException $exception) {
            throw new DbException($exception);
        }
    }

    /**
     * @param int $identify
     * @return int
     */
    public function delete(int $identify): int
    {
        try {
            $delete = Db::getInstance()->prepare("DELETE FROM {$this->table} WHERE {$this->prefix} = :{$this->prefix}", [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
            $delete->bindValue(":{$this->prefix}", $identify, \PDO::PARAM_INT);
            $delete->execute();

            return ($delete->rowCount() ?? 1);
        } catch (\PDOException $exception) {
            throw new DbException($exception);
        }
    }
}
