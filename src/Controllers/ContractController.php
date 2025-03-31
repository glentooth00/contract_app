<?php 

namespace App\Controllers;

use App\Config\Database;
use PDO;
use App\Controllers\CrudController;

class ContractController {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAllContracts() {
        $query = "SELECT * FROM contracts ORDER BY contract_start DESC";  // Assuming 'contract_start' is the date field
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function saveContract($data) {
        $query = "INSERT INTO contracts (contract_name, contract_type, contract_start, contract_end, contract_file) 
                  VALUES (:contract_name, :contract_type, :contract_start, :contract_end, :contract_file)";
        
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':contract_name' => $data['contract_name'],
            ':contract_type' => $data['contract_type'],
            ':contract_start' => $data['contract_start'],
            ':contract_end' => $data['contract_end'],
            ':contract_file' => $data['contract_file']
        ]);
    }

    // Upload file and return file path
    public function uploadFile($file) {
        $uploadDir = __DIR__ . "/../../admin/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "admin/uploads/" . $fileName; 
        }

        return false;
    }

    // Method to fetch contracts with pagination for SQL Server
    public function getContractsWithPagination($start, $limit) {
        // Ensure $start and $limit are integers
        $start = (int)$start;
        $limit = (int)$limit;
    
        // Use OFFSET and FETCH for pagination in SQL Server
        $sql = "SELECT * FROM contracts
                ORDER BY created_at DESC  -- Order by 'contract_start' date in descending order
                OFFSET {$start} ROWS
                FETCH NEXT {$limit} ROWS ONLY";
    
        $stmt = $this->db->prepare($sql);
    
        // Execute the query (no need for bound parameters here since we are injecting variables directly)
        $stmt->execute();
    
        // Fetch the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    // Method to get total number of contracts for pagination calculation
    public function getTotalContracts() {
        $sql = "SELECT COUNT(*) FROM contracts";
        $stmt = $this->db->query($sql);
        return $stmt->fetchColumn(); // Fetch the first column of the result
    }

    // Method to delete a contract by its ID
    public function deleteContract($id) {
    
        $sql = "DELETE FROM contracts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    

    
}
