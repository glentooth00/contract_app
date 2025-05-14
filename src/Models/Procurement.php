<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Procurement
{

    protected $db;

    protected $table;

    public function __construct(PDO $db, $table)
    {

        $this->db = Database::connect();
        $this->table = $table;

    }



}

