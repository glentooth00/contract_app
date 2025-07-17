<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;


class FlagController {

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function flagContract($data){

        $flagContract = "INSERT INTO flags (contract_id, flag_type, status) VALUES (:contract_id, :flag_type, :status)";
        $stmt = $this->db->prepare($flagContract);
        return $stmt->execute($data);

    }

    public function getFlag($id){

        $sql = "SELECT flag_type, status, contract_id FROM flags WHERE contract_id = :id";
        $stmt =  $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result;

    }

}