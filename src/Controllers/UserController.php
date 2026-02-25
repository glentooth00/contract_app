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
                'contract_types' => $user['contract_types'],
                'user_role' => $user['user_role'],
                'user_type' => $user['user_type']
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

    public function searchUsers($searchTerm, $page = 1, $limit = 8)
    {
        // Calculate offset
        $offset = ($page - 1) * $limit;

        // Main query with pagination
        $sql = "SELECT * FROM users
            WHERE firstname LIKE :search1 
               OR lastname LIKE :search2 
               OR username LIKE :search3
            ORDER BY firstname
            OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':search1', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->bindValue(':search2', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->bindValue(':search3', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count total records for pagination
        $countSql = "SELECT COUNT(*) FROM users
                 WHERE firstname LIKE :search1 
                    OR lastname LIKE :search2 
                    OR username LIKE :search3";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->bindValue(':search1', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $countStmt->bindValue(':search2', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $countStmt->bindValue(':search3', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $countStmt->execute();
        $totalRecords = $countStmt->fetchColumn();
        $totalPages = ceil($totalRecords / $limit);

        return [
            'results' => $results,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
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
        $sql = "INSERT INTO $this->table (firstname, middlename, lastname, user_role, department, gender, user_image, username, password, contract_types, user_type)
                VALUES (:firstname, :middlename, :lastname, :user_role, :department, :gender, :user_image, :username, :password, :contract_types, :user_type)";

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
        $stmt->bindParam(':contract_types', $data['contract_types'], PDO::PARAM_STR);
        $stmt->bindParam(':user_type', $data['user_type'], PDO::PARAM_STR);
        // Execute the query
        if ($stmt->execute()) {
            return "User has been successfully saved!";
        } else {
            return "Failed to save user.";
        }
    }

    public function getUserByDept($department)
    {

        $sql = "SELECT * FROM users WHERE department = :department";
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

    public function checkUsername($username)
    {
        $sql = 'SELECT username, id FROM users WHERE username = :username';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function changePass($data)
    {
        $sql = 'INSERT INTO change_password ( user_id, username, status ,created_at, updated_at ,request) VALUES ( :user_id, :username, :status, :created_at, :updated_at, :request)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_at', $data['updated_at']);
        $stmt->bindParam(':request', $data['request']);
        $stmt->execute();

        return;
    }


    public function getUserDataById($id)
    {

        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }


    public function updateUserimage($data, $id)
    {
        try {


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


            $sql = 'UPDATE users 
            SET user_image = :user_image WHERE id = :id';

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':id', $id['id']);

            $stmt->bindParam(':user_image', $data['user_image']);


            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'User updated successfully'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to execute statement'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function updateUserData($data, $id)
    {
        try {
            // SQL query to update user data
            $sql = "UPDATE users SET 
                    firstname = :firstname,
                    middlename = :middlename,
                    lastname = :lastname,
                    gender = :gender,
                    department = :department,
                    username = :username,
                    password = :password
                WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':firstname', $data['firstname']);
            $stmt->bindParam(':middlename', $data['middlename']);
            $stmt->bindParam(':lastname', $data['lastname']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':department', $data['department']);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':password', $data['password']);
            $stmt->bindParam(':id', $id['id']);

            if ($stmt->execute()) {
                return ['status' => 'success', 'message' => 'User updated successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to execute update statement.'];
            }
        } catch (\PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function updateRequest($id, $status)
    {

        $sql = "UPDATE change_password SET status = :status WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);

        return $this;

    }


    public function getUserpassword($id)
    {
        $sql = "SELECT password FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $result = $stmt->fetch();
    }


}
