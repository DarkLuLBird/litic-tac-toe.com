<?php

namespace application\lib;

use PDO;

class Db
{
    protected $db;

    public function __construct()
    {
        $config = require 'application/config/db.php';
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'], $config['user'], $config['password']);
    }

    public function query($sql, $params = [])
    {
        $statement = $this->db->prepare($sql);

        if(!empty($params)){
            foreach ($params as $key => $value) {
                $statement->bindValue(':'.$key, $value);
            }
        }
        $statement->execute();

        return $statement;
    }

    public function row($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function column($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        $result = $query->fetchColumn();
        return $result;
    }

    public function select($sql, $params = [])
    {
        $query = $this->query($sql, $params);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $result = $result[0];
        return $result;
    }
}