<?php 
namespace App\Controllers;

use App\Config\Database;
use PDO;
use App\Controllers\CrudController;

class EmploymentContractController {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function storeEmploymentRecord($data) {
        // Corrected SQL query with matching placeholders
        $sql = "INSERT INTO employment_history (status, contract_type, date_start, date_end, contract_name, contract_file) 
                VALUES (:status, :contract_type, :date_start, :date_end, :contract_name, :contract_file)";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':contract_type', $data['contract_type']);
        $stmt->bindParam(':date_start', $data['date_start']);
        $stmt->bindParam(':date_end', $data['date_end']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_file', $data['contract_file']);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function getByContractName($contractName) {
        $sql = "SELECT * FROM employment_history WHERE contract_name = :contract_name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_name', $contractName);
        $stmt->execute();
        
        // Fetch the result and return as an array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Use fetchAll to ensure it returns an array
    }
    
    
    

}