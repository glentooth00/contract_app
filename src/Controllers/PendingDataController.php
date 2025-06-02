<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;
use App\Controllers\CrudController;
use App\Models\User;

class PendingDataController
{

    private $db;

    private $table = "pending_data";

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function savePendingData($data)
    {
        $sql = "INSERT INTO " . $this->table . " (
                    contract_name,
                    contract_start,
                    contract_end,
                    contract_type,
                    contract_file,
                    created_at,
                    updated_at,
                    contract_status,
                    uploader_id,
                    uploader_department,
                    uploader,
                    approval_status,
                    data_type,
                    status
                ) VALUES (
                    :contract_name,
                    :contract_start,
                    :contract_end,
                    :contract_type,
                    :contract_file,
                    :created_at,
                    :updated_at,
                    :contract_status,
                    :uploader_id,
                    :uploader_department,
                    :uploader,
                    :approval_status,
                    :data_type,
                    :status
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':contract_start', $data['contract_start']);
        $stmt->bindParam(':contract_end', $data['contract_end']);
        $stmt->bindParam(':contract_type', $data['contract_type']);
        $stmt->bindParam(':contract_file', $data['contract_file']);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':contract_status', $data['contract_status']);
        $stmt->bindParam(':uploader_id', $data['uploader_id']);
        $stmt->bindParam(':uploader_department', $data['uploader_department']);
        $stmt->bindParam(':uploader', $data['uploader']);
        $stmt->bindParam(':approval_status', $data['approval_status']);
        $stmt->bindParam(':data_type', $data['data_type']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();

        return true;

    }

    public function checkPendingData()
    {
        $sql = "SELECT COUNT(*) as total FROM pending_data WHERE status = 1";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        }

        return 0;
    }


    public function getNewData($id)
    {
        $sql = "SELECT * FROM pending_data WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $result = $stmt->fetch();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM pending_data WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return;
    }

}