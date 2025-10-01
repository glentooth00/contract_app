<?php

namespace App\Controllers;

use PDOException;
use App\Config\Database;
use PDO;
use App\Controllers\CrudController;
use App\Models\Contract;

class ContractController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index($contact_filter)
    {
        $sql = "SELECT * FROM contracts WHERE contract_type = :contract_filter";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_filter', $contact_filter);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row instead of all
    }

    public function getAllContracts()
    {
        $query = "SELECT * FROM contracts ORDER BY contract_start DESC";  // Assuming 'contract_start' is the date field
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function saveContract($data)
    {

        $query = "INSERT INTO contracts (contract_name, contract_type, contract_start, contract_end, contract_file, contract_status, uploader_id,supplier, procurementMode ,uploader_department, department_assigned, implementing_dept, uploader, contractPrice) 
                  VALUES (:contract_name, :contract_type, :contract_start, :contract_end, :contract_file, :contract_status, :uploader_id, :supplier, :procurementMode , :uploader_department, :department_assigned, :implementing_dept, :uploader, :contractPrice)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':contract_name' => $data['contract_name'],
            ':contract_type' => $data['contract_type'],
            ':contract_start' => $data['contract_start'],
            ':contract_end' => $data['contract_end'],
            ':contract_file' => $data['contract_file'],
            ':contract_status' => $data['contract_status'],
            ':uploader_id' => $data['uploader_id'],
            ':procurementMode' => $data['procurementMode'],
            ':supplier' => $data['supplier'],
            ':uploader' => $data['uploader'],
            ':uploader_department' => $data['uploader_department'],
            ':department_assigned' => $data['department_assigned'] ?? null,
            ':implementing_dept' => $data['implementing_dept'] ?? null,
             ':contractPrice' => $data['contractPrice'] ?? null,

        ]);
    }

    // Upload file and return file path
    public function uploadFile($file)
    {
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
    public function getTotalContracts($type = null, $status = null, $search = null)
    {
        $sql = "SELECT COUNT(*) FROM contracts WHERE 1=1";

        if ($type) {
            $sql .= " AND contract_type = :type";
        }

        if ($status) {
            $sql .= " AND contract_status LIKE :status";
        }

        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }

        $stmt = $this->db->prepare($sql);

        if ($type) {
            $stmt->bindParam(':type', $type);
        }

        if ($status) {
            // Use LIKE workaround for TEXT columns
            $statusLike = $status;
            $stmt->bindParam(':status', $statusLike);
        }

        if ($search) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // Method to delete a contract by its ID
    public function deleteContract($id)
    {
        // Step 1: Get the contract file name first
        $getFileSql = "SELECT contract_file FROM contracts WHERE id = :id";
        $getFileStmt = $this->db->prepare($getFileSql);
        $getFileStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $getFileStmt->execute();
        $contract = $getFileStmt->fetch(PDO::FETCH_ASSOC);

        if ($contract && !empty($contract['contract_file'])) {
            // Step 2: Define the upload directory
            $uploadDir = __DIR__ . "/../../admin/uploads/";

            // Step 3: Construct full path and delete the file
            $filePath = $uploadDir . basename($contract['contract_file']);
            if (file_exists($filePath)) {
                unlink($filePath); // 🧹 Deletes the file
            }
        }

        // Step 4: Delete the database record
        $sql = "DELETE FROM contracts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getContractsWithPagination($start, $limit, $filter = null, $search = null)
    {
        $start = (int) $start;
        $limit = (int) $limit;

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

    public function getOldContractsWithPaginationAll($start, $limit, $typeFilter = null, $statusFilter = null, $search = null)
    {

        $start = max(0, (int) $start);  // Ensure start is non-negative
        $limit = max(1, (int) $limit);  // Ensure limit is at least 1

        $sql = "SELECT * FROM contracts WHERE 1=1";

        if ($typeFilter) {
            $sql .= " AND contract_type = :typeFilter";
        }

        if ($statusFilter) {
            $sql .= " AND contract_status = :statusFilter";
        }

        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }

        $sql .= " ORDER BY created_at ASC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";

        $stmt = $this->db->prepare($sql);

        if ($typeFilter) {
            $stmt->bindParam(':typeFilter', $typeFilter, PDO::PARAM_STR);
        }

        if ($statusFilter) {
            $stmt->bindParam(':statusFilter', $statusFilter, PDO::PARAM_STR);
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


    public function getOldContractsWithPagination($start, $limit, $assigned_dept, $uploader_dept, $filter = null, $search = null)
    {

        $start = max(0, (int) $start);  // Ensure start is non-negative
        $limit = max(1, (int) $limit);  // Ensure limit is at least 1

        // Base query for active contracts
        $sql = "SELECT * FROM contracts WHERE contract_status LIKE 'Active'";  // Using LIKE for compatibility with TEXT

        // Add condition for assigned department (for view permission)
        if ($assigned_dept) {
            $sql .= " AND department_assigned = :assigned_dept";
        }

        // Add condition for uploader department (for edit permission and creator filter)
        if ($uploader_dept) {
            $sql .= " AND uploader_department = :uploader_dept";
        }

        // Apply filter if present
        if ($filter) {
            $sql .= " AND contract_type = :filter";
        }

        // Apply search if present
        if ($search) {
            $sql .= " AND contract_name LIKE :search";
        }

        // Order by created_at to get the oldest contract first, and add pagination using OFFSET-FETCH
        $sql .= " ORDER BY created_at ASC OFFSET :start ROWS FETCH NEXT :limit ROWS ONLY";

        // Prepare and execute the statement
        $stmt = $this->db->prepare($sql);

        // Bind parameters if needed
        if ($assigned_dept) {
            $stmt->bindParam(':assigned_dept', $assigned_dept, PDO::PARAM_STR);
        }

        if ($uploader_dept) {
            $stmt->bindParam(':uploader_dept', $uploader_dept, PDO::PARAM_STR);
        }

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




    public function getOldContractsWithPaginationExpired($start, $limit, $filter = null, $search = null)
    {
        $start = max(0, (int) $start);  // Ensure start is non-negative
        $limit = max(1, (int) $limit);  // Ensure limit is at least 1

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



    public function getOldestContractsWithPagination($start, $limit, $filter = null, $search = null)
    {
        // Ensure start is non-negative and limit is at least 1
        $start = max(0, (int) $start);
        $limit = max(1, (int) $limit);

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




    public function updateContractStatus($data)
    {

        $sql = "UPDATE contracts SET
                contract_status = :contract_status,
                contract_start = :contract_start,
                contract_end = :contract_end,
                rent_start = :rent_start,
                rent_end = :rent_end,
                updated_at = :updated_at
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_id', $data['id']);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':rent_start', $data['rent_start']);
        $stmt->bindParam(':rent_end', $data['rent_start']);
        $stmt->bindParam(':updated_at', $data['updated_at']);

        $updateStatus = $stmt->execute();

        return $updateStatus;

    }


    public function updateTransRentContractStatus($data)
    {

        $sql = "UPDATE contracts SET
                contract_status = :contract_status,
                rent_end = :rent_end,
                updated_at = :updated_at
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_id', $data['id']);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':rent_end', $data['rent_end']);
        $stmt->bindParam(':updated_at', $data['updated_at']);

        $updateStatus = $stmt->execute();

        return $updateStatus;

    }

    public function updateTempLightContractStatus($data)
    {

        $sql = "UPDATE contracts SET
                contract_status = :contract_status,
                contract_end = :contract_end,
                updated_at = :updated_at
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_id', $data['id']);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':updated_at', $data['updated_at']);

        $updateStatus = $stmt->execute();

        return $updateStatus;

    }

    public function getWhereDepartment($department)
    {

        $sql = "SELECT uploader_department FROM contracts WHERE uploader_department = :department";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':department', $department);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getDepartmentAssigned($department)
    {

        $sql = "SELECT * FROM contracts WHERE department_assigned = :department";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':department', $department);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;

    }


    public function getContractbyId($id)
    {

        $sql = "SELECT * FROM contracts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;

    }

    public function getContract($data)
    {

        $sql = "SELECT * FROM contracts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $data['contract_id']);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;

    }

        public function getContractByIdUpdated($id)
        {

            $sql = "SELECT * FROM contracts WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $result = $stmt->fetch();

            return $result;

        }

    public function getContractbyIdPending($contract_id)
    {
        $sql = "SELECT * FROM contracts WHERE contract_id = :contract_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_id', $contract_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // return as array of rows
    }

    



    public function insertLatestData()
    {

        $sql = "SELECT TOP 1 * FROM contracts ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $insertLatest = "";



        echo $result['id'] . '<br>';
        echo $result['contract_name'] . '<br>';
        echo $result['contract_start'] . '<br>';
        echo $result['contract_end'] . '<br>';
        echo $result['contract_file'] . '<br>';
        echo $created_at = date('Y-m-d H:i:s') . '<br>';
        echo $updated_at = date('Y-m-d H:i:s') . '<br>';


        return;

    }

    public function updateContract($data)
    {
        $sql = "UPDATE contracts 
                SET 
                    contract_name = :contract_name,
                    contract_start = :contract_start,
                    contract_end = :contract_end,
                    -- department_assigned = :department_assigned,
                    updated_at = :updated_at,
                    contract_status = :contract_status,
                    contractPrice = :contractPrice,
                    contract_type = :contract_type
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_id', $data['id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['start']);
        $stmt->bindParam(':contract_end', $data['end']);
        // $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam('contract_status', $data['contract_status']);
        $stmt->bindParam('contractPrice', $data['contractPrice']);
        $stmt->bindParam(':contract_type', $data['contract_type']);
        // $stmt->bindParam('action_status', $data['action_status']);

        $stmt->execute();

        return true;
    }

    public function updateTransRentContract($data)
    {
        $sql = "UPDATE contracts 
                SET 
                    contract_name = :contract_name,
                    rent_start = :rent_start,
                    rent_end = :rent_end,
                    -- department_assigned = :department_assigned,
                    updated_at = :updated_at,
                    contract_status = :contract_status
                    -- action_status = :action_status
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_id', $data['id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':rent_start', $data['start']);
        $stmt->bindParam(':rent_end', $data['end']);
        // $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam('contract_status', $data['contract_status']);
        // $stmt->bindParam('action_status', $data['action_status']);

        $stmt->execute();

        return true;
    }
    public function updateContract1($data)
    {
        $sql = "UPDATE contracts 
            SET 
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end,
                department_assigned = :department_assigned,
                updated_at = :updated_at,
                contract_status = :contract_status
            WHERE id = :contract_id"; // <-- No commented lines in SQL string

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_id', $data['contract_id']); // use correct key
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['start']);
        $stmt->bindParam(':contract_end', $data['end']);
        $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $data['contract_status']);

        $stmt->execute();

        return true;
    }


    public function updateContractByAdmin($data)
    {
        $sql = "UPDATE contracts 
            SET 
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end,
                department_assigned = :department_assigned,
                updated_at = :updated_at,
                contract_status = :contract_status,
                action_status = :action_status
            WHERE id = :contract_id";  // use :id as in your $latestData

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_id', $data['contract_id']); // matches $latestData['id']
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']); // key corrected
        $stmt->bindParam(':contract_end', $data['contract_end']);     // key corrected
        $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':action_status', $data['action_status']);

        $stmt->execute();

        return true;
    }


    public function pendingUpdateContract($data)
    {
        $sql = "INSERT INTO incoming_data (contract_id, contract_name, start_date, end_date, user_dept, action_status, updated_at, created_at, updated_by, status) 
            VALUES (:contract_id, :contract_name, :start_date, :end_date, :user_dept, :action_status, :updated_at, :created_at, :updated_by, :status)";

        $stmt = $this->db->prepare($sql);

        // Correct bindings
        $stmt->bindParam(':contract_id', $data['contract_id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':user_dept', $data['user_dept']);
        $stmt->bindParam(':action_status', $data['action_status']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_by', $data['updated_by']);
        $stmt->bindParam(':status', $data['status']);

        $stmt->execute();

        return true;
    }



    public function renewContract($data)
    {

        $sql = "UPDATE contracts
                SET
                    contract_name = :contract_name,
                    contract_start = :contract_start,
                    contract_end = :contract_end,
                    department_assigned = :department_assigned,
                    contract_file = :contract_file,
                    contract_status = :contract_status,
                    updated_at = :updated_at
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_id', $data['contract_id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':contract_file', $data['contract_file']);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':updated_at', $data['updated_at']);

        $stmt->execute();

        return true;
    }

    public function createTempLightingContract($data)
    {
        $sql = "INSERT INTO contracts 
                    (TC_no, 
                    contract_start, 
                    contract_end, 
                    party_of_second_part, 
                    uploader,
                    uploader_id, 
                    uploader_department,
                    department_assigned,
                    created_at,
                    updated_at,
                    contract_status,
                    contract_file,
                    contract_type,
                    contract_name,
                    address,
                    account_no) 
                VALUES 
                    (:TC_no, 
                    :contract_start, 
                    :contract_end, 
                    :party_of_second_part, 
                    :uploader, 
                    :uploader_id, 
                    :uploader_dept,
                    :department_assigned,
                    :created_at,
                    :updated_at,
                    :contract_status,
                    :contract_file,
                    :contract_type,
                    :contract_name,
                    :address,
                    :account_no)";

        $stmt = $this->db->prepare($sql);

        // Set default values
        $contract_status = !empty($data['contract_status']) ? $data['contract_status'] : 'Active';
        // $department_assigned = ''; // <-- Always empty string

        $stmt->bindParam(':TC_no', $data['TC_no']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':party_of_second_part', $data['party_of_second_part']);
        $stmt->bindParam(':uploader', $data['uploader']);
        $stmt->bindParam(':uploader_id', $data['uploader_id']);
        $stmt->bindParam(':uploader_dept', $data['uploader_dept']);
        $stmt->bindParam(':department_assigned', $data['department_assigned']); // always empty
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $contract_status);
        $stmt->bindParam(':contract_file', $data['contract_file']);
        $stmt->bindParam(':contract_type', $data['contract_type']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':account_no', $data['account_no']);
        $stmt->bindParam(':contract_file', $data['contract_file']);

        $stmt->execute();

        return;
    }




    public function uploadRentalFile($file)
    {
        $uploadDir = __DIR__ . "/../../admin/rentals/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "admin/rentals/" . $fileName;
        }

        return false;
    }

    public function uploadRentalFileTR($file)
    {
        // Check if file is valid
        if (!isset($file) || !isset($file['name']) || !isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $uploadDir = __DIR__ . "/../../admin/rentals/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file["name"]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return "admin/rentals/" . $fileName;
        }

        return false;
    }


    public function storeTransFormerRental($data)
    {
        $sql = "INSERT INTO contracts (
                    contract_type, 
                    contract_name, 
                    contract_file, 
                    tc_no, 
                    rent_start, 
                    rent_end, 
                    contract_status, 
                    created_at, 
                    updated_at, 
                    uploader_department, 
                    uploader_id,
                    department_assigned,
                    account_no,
                    address
                ) VALUES (
                    :contract_type, 
                    :contract_name, 
                    :contract_file, 
                    :tc_no, 
                    :rent_start, 
                    :rent_end, 
                    :contract_status, 
                    :created_at, 
                    :updated_at, 
                    :uploader_department, 
                    :uploader_id,
                    :department_assigned,
                    :account_no,
                    :address
                )";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_type', $data['contract_type']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_file', $data['contract_file']);
        $stmt->bindParam(':tc_no', $data['tc_no']);
        $stmt->bindParam(':rent_start', $data['rent_start']);
        $stmt->bindParam(':rent_end', $data['rent_end']);


        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':uploader_department', $data['uploader_department']);
        $stmt->bindParam(':uploader_id', $data['uploader_id']);
        $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':account_no', $data['account_no']);
        $stmt->bindParam(':address', $data['address']);

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function getContractsByMSDDepartment($department, $searchQuery = null, $perPage = 10, $currentPage = 1, $contractTypeFilter = null)
    {
        $offset = ($currentPage - 1) * $perPage;

        $query = "SELECT * FROM contracts 
              WHERE (uploader_department = 'ISD-MSD' OR department_assigned = :department)";

        if ($searchQuery) {
            $query .= " AND contract_name LIKE :search_query";
        }

        if ($contractTypeFilter) {
            $query .= " AND contract_type = :contract_type";
        }

        // Order by latest created_at (or change to `id DESC` if no created_at exists)
        $query .= " ORDER BY created_at DESC
                OFFSET :offset ROWS
                FETCH NEXT :perPage ROWS ONLY";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':department', $department);

        if ($searchQuery) {
            $searchTerm = '%' . $searchQuery . '%';
            $stmt->bindParam(':search_query', $searchTerm);
        }

        if ($contractTypeFilter) {
            $stmt->bindParam(':contract_type', $contractTypeFilter);
        }

        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }



    public function getTotalContractsCount($department, $searchQuery = null, $contractTypeFilter = null)
    {
        $query = "SELECT COUNT(*) FROM contracts 
              WHERE (uploader_department = 'ISD-MSD' OR department_assigned = :department)";

        if ($searchQuery) {
            $query .= " AND contract_name LIKE :search_query";
        }

        if ($contractTypeFilter) {
            $query .= " AND contract_type = :contract_type";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':department', $department);

        if ($searchQuery) {
            $searchTerm = '%' . $searchQuery . '%';
            $stmt->bindParam(':search_query', $searchTerm);
        }

        if ($contractTypeFilter) {
            $stmt->bindParam(':contract_type', $contractTypeFilter);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function setExpired($id)
    {
        $sql = "UPDATE contracts SET contract_status = 'Expired' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return;
    }

    public function updateLightingContract($data)
    {
        $sql = "UPDATE contracts 
                SET 
                    contract_name = :contract_name,
                   contract_start = :contract_start,
                    contract_end = :contract_end,
                    updated_at = :updated_at 
                WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':contract_id', $data['id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']); // FIXED
        $stmt->bindParam(':contract_end', $data['contract_end']);     // FIXED
        $stmt->bindParam(':updated_at', $data['updated_at']);

        $stmt->execute();

        return true;
    }

    public function updateTransRent($data) // Accepting the full $data array
    {
        $sql = "UPDATE contracts 
                SET 
                    contract_name = :contract_name,
                    rent_start = :rent_start,
                    rent_end = :rent_end,
                    updated_at = :updated_at,
                    contract_status = :contract_status
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        // Binding parameters to the data array
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':rent_start', $data['rent_start']);
        $stmt->bindParam(':rent_end', $data['rent_end']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $data['contract_status']);


        // Executing the query
        $stmt->execute();

        return true;
    }

    public function updateTempLight($data) // Accepting the full $data array
    {
        $sql = "UPDATE contracts 
                SET 
                    contract_name = :contract_name,
                    contract_start = :contract_start,
                    contract_end = :contract_end,
                    updated_at = :updated_at 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        // Binding parameters to the data array
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':updated_at', $data['updated_at']);

        // Executing the query
        $stmt->execute();

        return true;
    }

    public function getContractsByDepartmentAll($department)
    {
        $query = "SELECT * FROM contracts 
              WHERE (uploader_department = :dept1 
                  OR implementing_dept = :dept2 
                  OR department_assigned = :dept3) 
              AND contract_status = 'Active' 
              ORDER BY id DESC";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':dept1', $department, PDO::PARAM_STR);
        $stmt->bindParam(':dept2', $department, PDO::PARAM_STR);
        $stmt->bindParam(':dept3', $department, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getContractsForAudit($department)
    {
       $query = "SELECT * FROM contracts WHERE contract_status = 'Active' ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);

        // $stmt->bindParam(':dept1', $department, PDO::PARAM_STR);
        // $stmt->bindParam(':dept2', $department, PDO::PARAM_STR);
        // $stmt->bindParam(':dept3', $department, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //could be just temporary to show all contracts by department
        public function getContractsAll()
    {
        $query = "SELECT * FROM contracts WHERE contract_status = 'Active' ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);

        // $stmt->bindParam(':dept1', $department, PDO::PARAM_STR);
        // $stmt->bindParam(':dept2', $department, PDO::PARAM_STR);
        // $stmt->bindParam(':dept3', $department, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractsAllExpired()
    {
        $query = "SELECT * FROM contracts WHERE contract_status = 'Expired' ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getContractsAllArchived()
    {
        $query = "SELECT * FROM contracts WHERE contract_status = 'Suspended' ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractsByDepartment($department)
    {
        $query = "SELECT * FROM contracts 
              WHERE (uploader_department = :dept1 
                  OR implementing_dept = :dept2 
                  OR department_assigned = :dept3) 
              AND contract_status = 'Active' 
              ORDER BY id DESC";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':dept1', $department, PDO::PARAM_STR);
        $stmt->bindParam(':dept2', $department, PDO::PARAM_STR);
        $stmt->bindParam(':dept3', $department, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExpiredContractsByDepartment($department)
    {
        // Corrected SQL query using a single WHERE clause
        $query = "SELECT * FROM contracts 
                  WHERE (uploader_department = ? OR department_assigned = ?) 
                  AND contract_status = 'Expired' 
                  ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindValue(1, $department, PDO::PARAM_STR);
        $stmt->bindValue(2, $department, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getArchivedContractsByDepartment($department)
    {
        // Corrected SQL query using a single WHERE clause
        $query = "SELECT * FROM contracts 
                  WHERE (uploader_department = ? OR department_assigned = ?) 
                  AND contract_status = 'Suspended' 
                  ORDER BY created_at DESC";

        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindValue(1, $department, PDO::PARAM_STR);
        $stmt->bindValue(2, $department, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //for CITETD contracts
    public function getTotalContractsCountCITETD($department, $searchQuery = null, $contractTypeFilter = null)
    {
        $query = "SELECT COUNT(*) FROM contracts 
              WHERE (uploader_department = 'CITETD' AND department_assigned = :department)";

        if ($searchQuery) {
            $query .= " AND contract_name LIKE :search_query";
        }

        if ($contractTypeFilter) {
            $query .= " AND contract_type = :contract_type";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':department', $department);

        if ($searchQuery) {
            $searchTerm = '%' . $searchQuery . '%';
            $stmt->bindParam(':search_query', $searchTerm);
        }

        if ($contractTypeFilter) {
            $stmt->bindParam(':contract_type', $contractTypeFilter);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public function savePowerSupplyContract($data)
    {

        $query = "INSERT INTO contracts (contract_name, contract_type, contract_start, contract_end, contract_file, contract_status, department_assigned, uploader_id, uploader_department, uploader, implementing_dept, approval_status) 
                  VALUES (:contract_name, :contract_type, :contract_start, :contract_end, :contract_file, :contract_status, :department_assigned, :uploader_id, :uploader_department, :uploader, :implementing_dept, :approval_status)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':contract_name' => $data['contract_name'],
            ':contract_type' => $data['contract_type'],
            ':contract_start' => $data['contract_start'],
            ':contract_end' => $data['contract_end'],
            ':contract_file' => $data['contract_file'],
            ':contract_status' => $data['contract_status'],
            ':department_assigned' => $data['department_assigned'],
            ':uploader_id' => $data['uploader_id'],
            ':uploader_department' => $data['uploader_department'],
            ':uploader' => $data['uploader'],
            ':implementing_dept' => $data['implementing_dept'],
            ':approval_status' => $data['approval_status']
        ]);
    }



    public function updateStatus($data)
    {

        $sql = "UPDATE contracts SET contract_status = :contract_status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':id', $data['id']);


        return $stmt->execute();

    }

    public function updateSuspension($data)
    {

        $sql = "UPDATE contracts SET 
                contract_status = :contract_status,
                contract_end = :contract_end
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':id', $data['id']);


        return $stmt->execute();

    }


    public function updateTransRentSuspension($data)
    {

        $sql = "UPDATE contracts SET 
                contract_status = :contract_status,
                rent_end = :rent_end
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':rent_end', $data['rent_end']);
        $stmt->bindParam(':id', $data['id']);


        return $stmt->execute();

    }


    public function updateStatusExpired($data)
    {
        $sql = 'UPDATE contracts SET contract_status = :contract_status WHERE id = :id ';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('contract_status', $data['contract_status']);
        $stmt->bindParam('id', $data['id']);

        return $stmt->execute();
    }

    public function storeContract($data)
    {
        try {
            // $formattedPrice = number_format((float) str_replace(',', '', $data["contractPrice"]), 2, '.', '');

            $sql = "INSERT INTO contracts (
                    contract_file,
                    contract_name,
                    contractPrice,
                    contract_start,
                    contract_end,
                    contract_status,
                    supplier,
                    contract_type,
                    procurementMode,
                    uploader_id,
                    uploader_department,
                    uploader,
                    implementing_dept
                ) VALUES (
                    :contract_file,
                    :contract_name,
                    :contractPrice,
                    :contract_start,
                    :contract_end,
                    :contract_status,
                    :supplier,
                    :contract_type,
                    :procurementMode,
                    :uploader_id,
                    :uploader_department,
                    :uploader,
                    :implementing_dept
                )";

            $stmt = $this->db->prepare($sql);

            // Bind all parameters, making sure contractPrice is passed as numeric
            $stmt->bindParam(":contract_file", $data["contract_file"]);
            $stmt->bindParam(":contract_name", $data["contract_name"]);
            $stmt->bindValue(":contractPrice", $data["contractPrice"]);
            $stmt->bindParam(":contract_start", $data["contract_start"]);
            $stmt->bindParam(":contract_end", $data["contract_end"]);
            $stmt->bindParam(":contract_status", $data["contract_status"]);
            $stmt->bindParam(":supplier", $data["supplier"]);
            $stmt->bindParam(":contract_type", $data["contract_type"]);
            $stmt->bindParam(":procurementMode", $data["procurementMode"]);
            $stmt->bindParam(":uploader", $data["uploader"]);
            $stmt->bindParam(":uploader_id", $data["uploader_id"]);
            $stmt->bindParam("uploader_department", $data["uploader_department"]);
            $stmt->bindParam("implementing_dept", $data["implementing_dept"]);

            $result = $stmt->execute();

            if (!$result) {
                $error = $stmt->errorInfo();
                echo '<pre>SQL Error: ' . print_r($error, true) . '</pre>';
                return false;
            }

            return true;

        } catch (\PDOException $e) {
            echo '<pre>PDO Exception: ' . $e->getMessage() . '</pre>';
            return false;
        }
    }


    public function getcontractByUsersId($userID)
    {
        $sql = "SELECT * FROM contracts WHERE uploader_id = :userID";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractsAssigned($departmentName)
    {
        $sql = 'SELECT * FROM contracts WHERE implementing_dept = :departmentName';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':departmentName', $departmentName);
        $stmt->execute();
        return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateGoodsContract($data) // Accepting the full $data array
    {
        $sql = "UPDATE contracts 
                SET 
                    contract_name = :contract_name,
                    contract_start = :contract_start,
                    contract_end = :contract_end,
                    updated_at = :updated_at,
                    contract_status = :contract_status
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        // Binding parameters to the data array
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $data['contract_status']);


        // Executing the query
        $stmt->execute();

        return true;
    }

    public function managerUpdate($data)
    {
        try {
            // Base SQL (without contract_type)
            $sql = "UPDATE contracts SET 
                uploader_department = :uploader_department,
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end,
                contractPrice = :contractPrice,
                procurementMode = :procurementMode,
                supplier = :supplier
                ";

            // Add contract_type only if not empty
            if (!empty($data['contract_type'])) {
                $sql .= ", contract_type = :contract_type";
            }

            $sql .= " WHERE id = :contract_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':uploader_department', $data['uploader_department']);
            $stmt->bindParam(':contract_name', $data['contract_name']);
            $stmt->bindParam(':contract_start', $data['contract_start']);
            $stmt->bindParam(':contract_end', $data['contract_end']);
            $stmt->bindParam(':contract_id', $data['contract_id']);
            $stmt->bindParam(':contractPrice', $data['contract_price']);
            $stmt->bindParam(':procurementMode', $data['procurementMode']);
            $stmt->bindParam(':supplier', $data['supplier']);


            // Only bind contract_type if not empty
            if (!empty($data['contract_type'])) {
                $stmt->bindParam(':contract_type', $data['contract_type']);
            }


            return $stmt->execute();
        } catch (PDOException $e) {
            echo 'PDO Error: ' . $e->getMessage();
            return false;
        }
    }


        public function managerUpdateINFRA($data)
    {
        try {
            $sql = "UPDATE contracts SET 
                uploader_department = :uploader_department,
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end,
                address = :address,
                supplier = :supplier,
                contract_type = :contract_type,
                contractPrice = :contractPrice
                WHERE id = :contract_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':uploader_department', $data['uploader_department']);
            $stmt->bindParam(':contract_name', $data['contract_name']);
            $stmt->bindParam(':contract_start', $data['contract_start']);
            $stmt->bindParam(':contract_end', $data['contract_end']);
            $stmt->bindParam(':contract_id', $data['contract_id']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':supplier', $data['supplier']);
            $stmt->bindParam(':contractPrice', $data['contractPrice']);
            $stmt->bindParam(':contract_type', $data['contract_type']);
            return $stmt->execute(); // returns true if successful
        } catch (PDOException $e) {
            echo 'PDO Error: ' . $e->getMessage(); // helpful during dev
            return false;
        }
    }

    public function managerUpdateGOODS($data)
    {
        $type = $data['contract_type_update'] ?? $data['contract_type'];

        try {
            $sql = "UPDATE contracts SET 
                uploader_department = :uploader_department,
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end,
                address = :address,
                supplier = :supplier,
                contract_type = :contract_type,
                contractPrice = :contractPrice,
                procurementMode = :procurementMode
                WHERE id = :contract_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':uploader_department', $data['uploader_department']);
            $stmt->bindParam(':contract_name', $data['contract_name']);
            $stmt->bindParam(':contract_start', $data['contract_start']);
            $stmt->bindParam(':contract_end', $data['contract_end']);
            $stmt->bindParam(':contract_id', $data['contract_id']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':supplier', $data['supplier']);
            $stmt->bindParam(':contractPrice', $data['contractPrice']);
            $stmt->bindParam(':contract_type',  $type);
            $stmt->bindParam(':procurementMode',  $data['procurementMode']);
            return $stmt->execute(); // returns true if successful
        } catch (PDOException $e) {
            echo 'PDO Error: ' . $e->getMessage(); // helpful during dev
            return false;
        }
    }

            public function managerUpdateTempLight($data)
{
    try {
        $sql = "UPDATE contracts SET 
            uploader_department = :uploader_department,
            contract_name = :contract_name,
            contract_start = :contract_start,
            contract_end = :contract_end,
            address = :address,
            supplier = :supplier,
            tc_no = :tc_no,
            contract_type = :contract_type,
            party_of_second_part = :party_of_second_part,
            account_no = :account_no

            WHERE id = :contract_id";
        
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':uploader_department', $data['uploader_department']);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':contract_id', $data['contract_id']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':party_of_second_part', $data['party_of_second_part']);
        $stmt->bindParam(':supplier', $data['supplier']); // supplier mapped to party_of_second_party
        $stmt->bindParam(':tc_no', $data['tc_no']);
        $stmt->bindParam(':contract_type', $data['contract_type']);
        $stmt->bindParam(':account_no', $data['account_no']);
        $stmt->bindParam(':party_of_second_part', $data['party_of_second_part']);

        return $stmt->execute(); // returns true if successful
    } catch (PDOException $e) {
        echo 'PDO Error: ' . $e->getMessage(); // helpful during dev
        return false;
    }
}


    public function managerUpdateTransRent($data)
    {
        try {
            $sql = "UPDATE contracts SET 
                uploader_department = :uploader_department,
                contract_name = :contract_name,
                rent_start = :rent_start,
                rent_end = :rent_end,
                contract_status = :contract_status,
                address = :address,
                tc_no = :tc_no,
                account_no = :account_no
                WHERE id = :contract_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':uploader_department', $data['uploader_department']);
            $stmt->bindParam(':contract_name', $data['contract_name']);
            $stmt->bindParam(':rent_start', $data['rent_start']);
            $stmt->bindParam(':rent_end', $data['rent_end']);
            $stmt->bindParam(':contract_id', $data['contract_id']);
            $stmt->bindParam(':contract_status', $data['contract_status']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':tc_no', $data['tc_no']);
            $stmt->bindParam(':account_no', $data['account_no']);
            $success = $stmt->execute();

                if($success){
                    $getLatestSql =  "SELECT * FROM contracts WHERE id = :contract_id";
                    $selectStmt = $this->db->prepare($getLatestSql);
                    $selectStmt->bindParam(':contract_id', $data['contract_id']);
                    $selectStmt->execute();

                    return $selectStmt->fetch(PDO::FETCH_ASSOC);
                }

            return false ; // returns true if successful
        } catch (PDOException $e) {
            echo 'PDO Error: ' . $e->getMessage(); // helpful during dev
            return false;
        }
    }

    public function updateEmpContract(){

        $sql = "UPDATE contracts SET
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end
                WHERE id = :contract_id";
            
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':contract_id', $data['contract_id']);
        return $stmt->execute(); // returns true if successful
    }


    public function managerUpdateSACC($data)
{
    try {
        $sql = "UPDATE contracts SET 
            uploader_department = :uploader_department,
            contract_name = :contract_name,
            contract_start = :contract_start,
            contract_end = :contract_end,
            contract_type = :contract_type,
            contractPrice = :contractPrice
            WHERE id = :contract_id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':uploader_department' => $data['uploader_department'],
            ':contract_name' => $data['contract_name'],
            ':contract_start' => $data['contract_start'],
            ':contract_end' => $data['contract_end'],
            ':contractPrice' => $data['contractPrice'],
            ':contract_id' => $data['contract_id'],
            ':contract_type' => $data['contract_type_update']
        ]);

    } catch (PDOException $e) {
        echo 'PDO Error: ' . $e->getMessage(); 
        return false;
    }
}


    public function updateStatusById($data){

        echo  $data['contract_id'] .' '. $data['contract_status'];

        $sql = "UPDATE contracts SET contract_status = :contract_status WHERE id = :contract_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':contract_id', $data['contract_id']);
        return $stmt->execute();
    }


}
