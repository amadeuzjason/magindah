<?php

namespace App\Services;

class DataService
{
    protected $sqlite;

    public function __construct()
    {
        $this->sqlite = new SQLiteService();
    }

    public function query($sql, $params = [])
    {
        return $this->sqlite->query($sql, $params);
    }

    public function execute($sql, $params = [])
    {
        return $this->sqlite->execute($sql, $params);
    }

    public function getColumns($table)
    {
        return $this->sqlite->getColumns($table);
    }
}
