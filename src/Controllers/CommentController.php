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
        $query = "INSERT INTO comments (contract_id, audit_id, comment, comment_id, status) VALUES (:contract_id, :audit_id, :comment, :comment_id, :status)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function saveCommentForUser($data)
    {
        $query = "INSERT INTO comments (contract_id, user_id, comment, comment_id, status) VALUES (:contract_id, :user_id, :comment, :comment_id, :status)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function hasComment($contractId)
    {
        $query = "SELECT * FROM comments WHERE contract_id = :contract_id AND status = '1'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contract_id', $contractId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function hasCommentCount($contractId)
    {
        $query = "SELECT COUNT(*) as comment_count FROM comments WHERE contract_id = :contract_id AND status = '1'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contract_id', $contractId);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int) $result['comment_count'] : 0;
    }

    public function getCommentsByContractId($contractId)
    {
        $query = "SELECT * FROM comments WHERE contract_id = :contract_id AND status = '1'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contract_id', $contractId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function getComments($contractId)
    {
        $query = "SELECT * FROM comments WHERE contract_id = :contract_id ORDER BY created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contract_id', $contractId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCommentStatus($data)
    {
        $query = "UPDATE comments SET status = :status WHERE contract_id = :contract_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':contract_id', $data['contract_id']);
        return $stmt->execute();
    }


}