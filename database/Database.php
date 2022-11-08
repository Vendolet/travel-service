<?php

namespace database;

use Krugozor\Database\Mysql;

class Database
{
    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password,
        private string $charset,
    ){}

    /**
     * Подключение к БД
     *
     * @return object MySql
     * @throws MySqlException
     */
    public function getConnect()
    {
        // mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

        $db = Mysql::create($this->host, $this->username, $this->password)
                ->setDatabaseName($this->dbname)
                ->setCharset($this->charset);

        return $db;
    }
}
