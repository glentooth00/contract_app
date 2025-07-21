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

    public function PendingUpdate($data)
    {
        $sql = "UPDATE pending_data SET 
                contract_name = :contract_name,
                contract_start = :contract_start,
                contract_end = :contract_end,
                updated_at = :updated_at,
                contract_status = :contract_status,
                status = :status
            WHERE contract_id = :contract_id"; // WHERE goes after SET

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':contract_name' => $data['contract_name'],
            ':contract_start' => $data['contract_start'],
            ':contract_end' => $data['contract_end'],
            ':updated_at' => $data['updated_at'],
            ':contract_status' => $data['contract_status'],
            ':status' => $data['status'],
            ':contract_id' => $data['contract_id'], // WHERE condition must come last
        ]);
    }


    public function PendingInsert($data)
    {
        $sql = "INSERT INTO pending_data (
                contract_id,
                contract_name,
                contract_start,
                created_at,
                contract_end,
                updated_at,
                contract_status,
                contract_type,
                uploader_id,
                uploader,
                data_type,
                uploader_department,
                status,
                updated_by
            ) VALUES (
                :contract_id,
                :contract_name,
                :contract_start,
                :created_at,
                :contract_end,
                :updated_at,
                :contract_status,
                :contract_type,
                :uploader_id,
                :uploader,
                :data_type,
                :uploader_department,
                :status,
                :updated_by
            )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':contract_id' => $data['contract_id'],
            ':contract_name' => $data['contract_name'],
            ':contract_start' => $data['contract_start'],
            ':created_at' => $data['created_at'],
            ':contract_end' => $data['contract_end'],
            ':updated_at' => $data['updated_at'],
            ':contract_status' => $data['contract_status'],
            ':contract_type' => $data['contract_type'],
            ':uploader_id' => $data['uploader_id'],
            ':uploader' => $data['uploader'],
            ':data_type' => $data['data_type'],
            ':uploader_department' => $data['uploader_department'],
            ':status' => $data['status'],
            ':updated_by' => $data['updated_by']
        ]);


    }


    public function updatedInfraData($data){

        $sql = "INSERT INTO pending_data (
                    contract_id,
                    contract_name,
                    contract_start,
                    contract_end,
                    contract_type,
                    contract_file,
                    created_at,
                    updated_at,
                    uploader_id,
                    contract_status,
                    uploader_department,
                    uploader,
                    approval_status,
                    status,
                    data_type,
                    updated_by
                ) VALUES (

                )";

    }



}