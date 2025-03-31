<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;
use App\Controllers\CrudController;
class UserController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getUsers() {
        $table = "users"; // Change to your table

        if ($this->db instanceof PDO) {
            try {
                $stmt = $this->db->query("SELECT * FROM $table");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (\PDOException $e) {
                return "SQL Server Query failed: " . $e->getMessage();
            }
        } elseif ($this->db instanceof mysqli) {
            $query = "SELECT * FROM $table";
            $result = $this->db->query($query);

            if ($result) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                return "MySQL Query failed: " . $this->db->error;
            }
        } else {
            return "No valid database connection.";
        }
    }

    public function authenticate($data){

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindValue(':username',$data['username'],PDO::PARAM_STR);
        $stmt->bindValue(':password',$data['password'],PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a user is found
        if ($user) {

            $_SESSION['data'] = $userData = [
                'id' => $user['id'],
                'username' => $user['username'],
                'password' => $user['password'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'middlename' => $user['middlename'],
            ];

        } else {
            // No user found with the provided credentials
            return false; // Failed authentication
        }

    }
}
