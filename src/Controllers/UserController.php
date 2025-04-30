<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;
use mysqli;
use App\Controllers\CrudController;
class UserController
{
    private $db;

    private $table = "users";

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // public function getUsers() {
    //     $table = "users"; // Change to your table

    //     if ($this->db instanceof PDO) {
    //         try {
    //             $stmt = $this->db->query("SELECT * FROM $table");
    //             return $stmt->fetchAll(PDO::FETCH_ASSOC);
    //         } catch (\PDOException $e) {
    //             return "SQL Server Query failed: " . $e->getMessage();
    //         }
    //     } elseif ($this->db instanceof mysqli) {
    //         $query = "SELECT * FROM $table";
    //         $result = $this->db->query($query);

    //         if ($result) {
    //             return $result->fetch_all(MYSQLI_ASSOC);
    //         } else {
    //             return "MySQL Query failed: " . $this->db->error;
    //         }
    //     } else {
    //         return "No valid database connection.";
    //     }
    // }

    public function authenticate($data)
    {

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindValue(':password', $data['password'], PDO::PARAM_STR);
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
                'department' => $user['department'],
            ];

        } else {
            // No user found with the provided credentials
            return false; // Failed authentication
        }

    }

    public function getAllUsers()
    {

        $sql = "SELECT * FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    public function getUserRolebyId($id)
    {
        $sql = "SELECT user_role FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['user_role'] : null;
    }

    public function deleteUser($id)
    {

        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return;
    }

    public function storeUser($data)
    {
        // Check if the image is uploaded
        if (isset($_FILES['user_image'])) {
            $user_image = $_FILES['user_image'];

            // Check if the file was uploaded without errors
            if ($user_image['error'] == 0) {
                // Define the target directory
                $uploadDir = realpath(__DIR__ . "/../../admin/user_image/");

                if (!$uploadDir) {
                    return "The target directory does not exist.";
                }

                // Generate a unique name for the file to prevent overwriting
                $imageName = uniqid() . "_" . basename($user_image['name']);

                // Define the target file path
                $targetFilePath = $uploadDir . DIRECTORY_SEPARATOR . $imageName;

                // Move the uploaded file to the target directory
                if (!move_uploaded_file($user_image['tmp_name'], $targetFilePath)) {
                    return "Error uploading image.";
                }

                // Store the image name in the data array
                $data['user_image'] = $imageName;
            } else {
                return "Error uploading image.";
            }
        }

        // Hash the password before saving to the database
        // if (isset($data['password'])) {
        //     $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        // } else {
        //     return "Password is required.";
        // }

        // Prepare SQL to insert user data (with or without image)
        $sql = "INSERT INTO $this->table (firstname, middlename, lastname, user_role, department, gender, user_image, username, password)
                VALUES (:firstname, :middlename, :lastname, :user_role, :department, :gender, :user_image, :username, :password)";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':firstname', $data['firstname'], PDO::PARAM_STR);
        $stmt->bindParam(':middlename', $data['middlename'], PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $data['lastname'], PDO::PARAM_STR);
        $stmt->bindParam(':user_role', $data['user_role'], PDO::PARAM_STR);
        $stmt->bindParam(':department', $data['department'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $data['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':user_image', $data['user_image'], PDO::PARAM_STR); // Image filename
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR); // Password can be hashed before storing

        // Execute the query
        if ($stmt->execute()) {
            return "User has been successfully saved!";
        } else {
            return "Failed to save user.";
        }
    }

    public function getUserByDept($department)
    {

        $sql = "SELECT id, department FROM users WHERE department = :department";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':department', $department, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getUserDepartmentById($id)
    {

        $sql = "SELECT department FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getUserById($id)
    {

        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;

    }


}
