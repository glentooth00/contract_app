<?php 

namespace App\Controllers;

use PDOException;
use App\Config\Database;
use PDO;




class CommentController{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveComment($data)
    {
        $query = "INSERT INTO comments (contract_id, audit_id, comment, comment_id) VALUES (:contract_id, :audit_id, :comment, :comment_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

}