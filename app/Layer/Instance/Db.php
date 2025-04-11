<?php

namespace App\Layer\Instance;

use App\Layer\Exception\DbException;

class Db
{
    private static $instance;

    /**
     * @throws DbException
     * @return null|PDO
    */
    public static function getInstance(): ?\PDO
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new \PDO(
                    'sqlsrv:Server=' . $_ENV['CONFIG_DB_HOST'] . ';Database=' . $_ENV['CONFIG_DB_DATA'] . ';',
                    $_ENV['CONFIG_DB_USER'],
                    $_ENV['CONFIG_DB_PASS'],
                    [
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                    ]
                );
            } catch (\PDOException $exception) {
                throw new DbException($exception);
            }
        }
        return self::$instance;
    }
}
