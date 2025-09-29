<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;
use App\Controllers\UserController;


class TestController{

        private $db;

    private $table = "users";

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getContracts($data){

        $sql = "SELECT * FROM pending_data WHERE id = :id ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        $stmt->execute();
        return $result = $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function getCurrent($data){
    
        $query = "SELECT * FROM contracts WHERE id = :contract_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contract_id', $data['contract_id']);
        $stmt->execute();
        return $result = $stmt->fetch(PDO::FETCH_ASSOC);


    }

}