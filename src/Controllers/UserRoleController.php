<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\User_Roles;
use PDO;
use mysqli;
class UserRoleController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function saveRole($data)
    {
        $sql = "INSERT INTO user_roles (user_role) VALUES (:user_role)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_role', $data);
        $stmt->execute();
    }

    public function getRoles()
    {
        $sql = "SELECT * FROM user_roles";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function deleteRole($id)
    {

        $sql = "DELETE FROM user_roles WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return;
    }
}