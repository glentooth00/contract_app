<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;
use App\Controllers\CrudController;
use App\Models\User;

class ProcurementController
{

    private $db;

    private $table = "procurement";

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function storeProcMode($data)
    {
        try {
            $sql = "INSERT INTO " . $this->table . " (procMode, created_at, updated_at) 
                VALUES (:procMode, :created_at, :updated_at)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":procMode", $data["procMode"], PDO::PARAM_STR);
            $stmt->bindParam(":created_at", $data["created_at"], PDO::PARAM_STR);
            $stmt->bindParam(":updated_at", $data["updated_at"], PDO::PARAM_STR);

            $result = $stmt->execute();

            if (!$result) {
                // Debug SQL error
                $error = $stmt->errorInfo();
                echo '<pre>SQL Error: ' . print_r($error, true) . '</pre>';
            }

            return $result;

        } catch (\PDOException $e) {
            echo '<pre>PDO Exception: ' . $e->getMessage() . '</pre>';
            return false;
        }
    }


    public function list()
    {
        try {

            $sql = " SELECT * FROM " . $this->table;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;

            if (!$result) {
                // Debug SQL error
                $error = $stmt->errorInfo();
                echo '<pre>SQL Error: ' . print_r($error, true) . '</pre>';
            }
        } catch (\PDOException $e) {
            echo '<pre>PDO Exception: ' . $e->getMessage() . '</pre>';
            return false;
        }
    }

    public function deleteMode($id)
    {
        try {
            $sql = " DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->execute();

            return $result;

            if (!$result) {
                // Debug SQL error
                $error = $stmt->errorInfo();
                echo '<pre>SQL Error: ' . print_r($error, true) . '</pre>';
            }

        } catch (\PDOException $e) {
            echo '<pre>PDO Exception: ' . $e->getMessage() . '</pre>';
            return false;
        }
    }

    public function getAllProcMode()
    {
        try {

            $sql = "SELECT * FROM " . $this->table;
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;

            if (!$result) {
                // Debug SQL error
                $error = $stmt->errorInfo();
                echo '<pre>SQL Error: ' . print_r($error, true) . '</pre>';
            }

        } catch (\PDOException $e) {
            echo '<pre>PDO Exception: ' . $e->getMessage() . '</pre>';
            return false;
        }
    }

}