<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use App\Controllers\CrudController;
use App\Models\Contract;


class ContractHistoryController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function insertLatestData()
    {
        $sql = "SELECT TOP 1 * FROM contracts ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // var_dump($result);

        if (!$result) {
            return false;
        }

        $contract_id = $result['id'];

        // ✅ Check if this contract is already in employment_history
        $checkSql = "SELECT COUNT(*) FROM contract_history WHERE contract_id = :contract_id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bindParam(':contract_id', $contract_id);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();


        if ($exists > 0) {
            // Already exists, don't insert again
            return false;
        }

        // Prepare data
        $contract_name = $result['contract_name'];
        $contract_type = $result['contract_type'];
        $date_start = $result['contract_start'];
        $date_end = $result['contract_end'];
        $rent_start = $result['rent_start'];
        $rent_end = $result['rent_end'];
        $contract_file = $result['contract_file'];
        $status = 'Active';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $insertLatest = "INSERT INTO contract_history (
            status, contract_type, date_start, date_end, contract_name, contract_file, contract_id, created_at, updated_at, rent_start, rent_end
        ) VALUES (
            :status, :contract_type, :date_start, :date_end, :contract_name, :contract_file, :contract_id, :created_at, :updated_at, :rent_start, :rent_end
        )";

        $stmt = $this->db->prepare($insertLatest);

        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':contract_type', $contract_type);
        $stmt->bindParam(':date_start', $date_start);
        $stmt->bindParam(':date_end', $date_end);
        $stmt->bindParam(':rent_start', $rent_start);
        $stmt->bindParam('rent_end', $rent_end);
        $stmt->bindParam(':contract_name', $contract_name);
        $stmt->bindParam(':contract_file', $contract_file);
        $stmt->bindParam(':contract_id', $contract_id);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);

        return $stmt->execute();
    }

    public function getByContractId($id)
    {
        $sql = "SELECT * FROM contract_history WHERE contract_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result and return as an array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Use fetchAll to ensure it returns an array
    }


    public function udpateExpiredRentalContract($data)
    {

        $sql = "UPDATE contract_history SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();

    }

    public function updatedExpired($data)
    {
        $sql = "UPDATE contract_history SET status = :status WHERE contract_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        return $stmt->execute();


    }


}