<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;

class CrudController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function select(){
        $sql = "SELECT";
    }


}
