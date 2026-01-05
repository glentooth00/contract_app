<?php 

namespace App\Controllers;

use PDOException;
use App\Config\Database;
use PDO;




class ChangePasswordController{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getChangePasswordRequests()
    {
        $query = "SELECT * FROM change_password ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
