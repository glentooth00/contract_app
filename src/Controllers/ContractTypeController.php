<?php 

namespace App\Controllers;

use App\Config\Database;
use PDO;
use App\Controllers\CrudController;

class ContractTypeController{
    
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }


    public function insertContractType($data){
        
        $sql = "INSERT INTO contract_types (contract_type, contract_duration,contract_ert) VALUES (:contract_type, :contract_duration, :contract_ert)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_type', $data['contract_type']);
        $stmt->bindParam(':contract_duration', $data['contract_duration']);
        $stmt->bindParam(':contract_ert', $data['contract_ert']);
        $stmt->execute();

        return;
    }


    public function getContractTypes(){

        $sql = "SELECT * FROM contract_types";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        

    }


    public function deleteContractType($data){

        $sql = "DELETE FROM contract_types WHERE id = :id ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        

        return $stmt->execute();

    }

}