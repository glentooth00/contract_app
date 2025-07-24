<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;
use App\Controllers\CrudController;
use App\Models\User;

class NotificationController
{


    private $db;

    private $table = "procurement";

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function checkRecentUpdates()
    {
        $sql = "SELECT COUNT(*) as total FROM pending_data WHERE status = 1";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        }

        return 0;
    }

    public function displayAllPendingUpdates()
    {
        $sql = "SELECT * FROM pending_data WHERE status = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;


    }

        public function displayAllPendingUpdatesForISD($department)
    {
        $sql = "SELECT * FROM pending_data WHERE uploader_department = :uploader_dept OR assigned_dept = :assigned_dept  AND status = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':uploader_dept',$department);
        $stmt->bindParam(':assigned_dept',$department);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;


    }

    public function getPendingDatabyId($id)
    {

        $sql = "SELECT * FROM pending_data WHERE contract_id = :id ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam('id', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;

    }


    public function updateContract($data)
    {
        $sql = "UPDATE incoming_data
            SET 
                contract_name = :contract_name,
               start_date = :start_date,
                end_date = :end_date,
                -- department_assigned = :department_assigned,
                updated_at = :updated_at,
                status = :status
            WHERE contract_id = :id";  // use :id as in your $latestData

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $data['id']); // matches $latestData['id']
        $stmt->bindParam(':contract_name', $data['contract_name']);
        $stmt->bindParam(':start_date', $data['start_date']); // key corrected
        $stmt->bindParam(':end_date', $data['end_date']);     // key corrected
        // $stmt->bindParam(':department_assigned', $data['department_assigned']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':status', $data['status']);

        $stmt->execute();

        return true;
    }



    public function delete($data)
    {
        $sql = "DELETE FROM incoming_data WHERE id = :id";
        $stmt = $this->db->prepare($sql); // use prepare() instead of query()
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
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
                    approval_status
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
                    :approval_status
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
        $stmt->execute();

        return true;

    }



}