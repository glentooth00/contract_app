<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

class SuspensionController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveSuspension($data)
    {
        $sql = "INSERT INTO tbl_suspension (type_of_suspension, no_of_days, reason, contract_id, account_no, created_at, updated_at, contract_status) VALUES (:type_of_suspension, :no_of_days, :reason, :contract_id, :account_no, :created_at, :updated_at, :contract_status)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':type_of_suspension', $data['type_of_suspension']);
        $stmt->bindParam(':no_of_days', $data['no_of_days']);
        $stmt->bindParam(':reason', $data['reason']);
        $stmt->bindParam(':contract_id', $data['contract_id']);
        $stmt->bindParam(':account_no', $data['account_no']);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $data['contract_status']);


        return $stmt->execute();

    }

    public function getSuspensionByAccount_no($id)
    {
        $sql = "SELECT * FROM tbl_suspension WHERE account_no = :id AND contract_status = 'Suspended'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteSuspension($id)
    {

        $sql = "DELETE FROM tbl_suspension WHERE contract_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id);
        return $stmt->execute();

    }

}