<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User_Roles
{
    private $db;
    private $sql;
    private $stmt;


    private $table = "user_roles";

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function insertRole($data)
    {
        echo $sql = " INSERT INTO " . $this->table . " (user_role) VALUES (:user_role)";

        // return;
    }

    public function query($sql)
    {
        $this->sql = $sql;
        $sql = $this->db->prepare($this->sql);

        return $this;
    }

    public function bind(array $params)
    {
        foreach ($params as $key => $value) {
            $this->stmt->bindValue($key, $value);
        }
        return $this;
    }

    public function execute()
    {
        $this->stmt->execute();
        return $this;
    }

    public function result()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

}