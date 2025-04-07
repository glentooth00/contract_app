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

    public function index($contact_filter) {
        $sql = "SELECT * FROM contracts WHERE contract_type = :contract_filter";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_filter', $contact_filter);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row instead of all
    }
    
    

    public function getAllContracts() {
        $query = "SELECT * FROM contracts ORDER BY contract_start DESC";  // Assuming 'contract_start' is the date field
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function saveContract($data) {
        $query = "INSERT INTO contracts (contract_name, contract_type, contract_start, contract_end, contract_file, contract_status) 
                  VALUES (:contract_name, :contract_type, :contract_start, :contract_end, :contract_file, :contract_status)";
        
        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':contract_name' => $data['contract_name'],
            ':contract_type' => $data['contract_type'],
            ':contract_start' => $data['contract_start'],
            ':contract_end' => $data['contract_end'],
            ':contract_file' => $data['contract_file'],
            ':contract_status' => $data['contract_status']
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

    public function getContractsWithPagination($start, $limit, $filter = null, $search = null) {
        $start = (int)$start;
        $limit = (int)$limit;
    
        $sql = "SELECT * FROM contracts WHERE 1=1";
    
        if ($filter) {
            $sql .= " AND contract_type = :filter";
        }
    
        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }
    
        $sql .= " ORDER BY created_at DESC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";
    
        $stmt = $this->db->prepare($sql);
    
        if ($filter) {
            $stmt->bindParam(':filter', $filter, PDO::PARAM_STR);
        }
    
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
    
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOldContractsWithPaginationAll($start, $limit, $filter = null, $search = null) {
        $start = max(0, (int)$start);  // Ensure start is non-negative
        $limit = max(1, (int)$limit);  // Ensure limit is at least 1
    
        // Build base SQL query with casting contract_status to VARCHAR
        $sql = "SELECT * FROM contracts WHERE 1=1";
    
        // Apply filter if present
        if ($filter) {
            $sql .= " AND contract_type = :filter";
        }
    
        // Apply search if present
        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }
    
        // Order by created_at to get the oldest contract first
        $sql .= " ORDER BY created_at ASC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";
    
        // Prepare and execute the statement
        $stmt = $this->db->prepare($sql);
    
        // Bind parameters if needed
        if ($filter) {
            $stmt->bindParam(':filter', $filter, PDO::PARAM_STR);
        }
    
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
    
        // Bind pagination parameters
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    
        // Execute query
        $stmt->execute();
    
        // Return the result as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOldContractsWithPagination($start, $limit, $filter = null, $search = null) {
        $start = max(0, (int)$start);
        $limit = max(1, (int)$limit);
    
        $sql = "SELECT * FROM contracts WHERE contract_status = :status";
        $params = ['status' => 'Active'];
    
        if ($filter) {
            $sql .= " AND contract_type = :filter";
            $params['filter'] = $filter;
        }
    
        if ($search) {
            $sql .= " AND contract_name LIKE :search";
            $params['search'] = "%$search%";
        }
    
        $sql .= " ORDER BY created_at ASC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";
        $params['start'] = $start;
        $params['limit'] = $limit;
    
        $stmt = $this->db->prepare($sql);
    
        foreach ($params as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$key", $value, $paramType);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOldContractsWithPaginationExpired($start, $limit, $filter = null, $search = null) {
        $start = max(0, (int)$start);  // Ensure start is non-negative
        $limit = max(1, (int)$limit);  // Ensure limit is at least 1
    
        // Build base SQL query with casting contract_status to VARCHAR
        $sql = "SELECT * FROM contracts WHERE CAST(contract_status AS VARCHAR(255)) = 'Expired'";
    
        // Apply filter if present
        if ($filter) {
            $sql .= " AND contract_type = :filter";
        }
    
        // Apply search if present
        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }
    
        // Order by created_at to get the oldest contract first
        $sql .= " ORDER BY created_at ASC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";
    
        // Prepare and execute the statement
        $stmt = $this->db->prepare($sql);
    
        // Bind parameters if needed
        if ($filter) {
            $stmt->bindParam(':filter', $filter, PDO::PARAM_STR);
        }
    
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
    
        // Bind pagination parameters
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    
        // Execute query
        $stmt->execute();
    
        // Return the result as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    public function getOldestContractsWithPagination($start, $limit, $filter = null, $search = null) {
        // Ensure start is non-negative and limit is at least 1
        $start = max(0, (int)$start);
        $limit = max(1, (int)$limit);
    
        // Initialize the SQL query
        $sql = "SELECT * FROM contracts WHERE contract_status != 'Expired'";
    
        // Add filter condition if it's provided
        if ($filter) {
            $sql .= " AND contract_type = :filter";
        }
    
        // Add search condition if it's provided
        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }
    
        // Order by contract_start in ascending order to get the oldest contracts first
        $sql .= " ORDER BY contract_start ASC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";
    
        // Prepare the statement
        $stmt = $this->db->prepare($sql);
    
        // Bind the filter parameter if provided
        if ($filter) {
            $stmt->bindParam(':filter', $filter, PDO::PARAM_STR);
        }
    
        // Bind the search parameter if provided
        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
    
        // Bind the start and limit parameters for pagination
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    
        // Execute the query
        $stmt->execute();
    
        // Fetch the result as an associative array
        $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Get the total number of records to calculate total pages
        $countQuery = "SELECT COUNT(*) FROM contracts WHERE contract_status != 'Expired'";
    
        // Add the filter condition again to the count query if provided
        if ($filter) {
            $countQuery .= " AND contract_type = :filter";
        }
    
        // Add the search condition again to the count query if provided
        if ($search) {
            $countQuery .= " AND contract_name LIKE :search";
        }
    
        // Prepare the count query
        $countStmt = $this->db->prepare($countQuery);
    
        // Bind the filter parameter if provided
        if ($filter) {
            $countStmt->bindParam(':filter', $filter, PDO::PARAM_STR);
        }
    
        // Bind the search parameter if provided
        if ($search) {
            $searchTerm = "%$search%";
            $countStmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
    
        // Execute the count query
        $countStmt->execute();
    
        // Fetch the total record count
        $totalRecords = $countStmt->fetchColumn();
    
        // Calculate total pages, ensuring at least 1 page is shown
        $totalPages = ceil($totalRecords / $limit);
        if ($totalPages == 0) {
            $totalPages = 1;  // Ensure at least 1 page is displayed
        }
    
        return ['contracts' => $contracts, 'totalPages' => $totalPages];
    }
    
    
    
    
    public function updateContractStatus($contract_id, $contract_status){

        $sql = "UPDATE contracts SET contract_status = :contract_status WHERE id = :contract_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_id', $contract_id);
        $stmt->bindParam(':contract_status', $contract_status);
        $test = $stmt->execute();

    // Check if the query was successful
        // if ($test) {
        //     echo "Query executed successfully!";
        //     echo "Rows affected: " . $stmt->rowCount();
        // } else {
        //     // Output any error information
        //     var_dump($stmt->errorInfo());
        // }

        return;

    }

    
    
    

    
}
