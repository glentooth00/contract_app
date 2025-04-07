<?php 
namespace App\Controllers;

use App\Config\Database;
use PDO;
use App\Controllers\CrudController;

class DepartmentController{
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function addDepartment($departmentName){

        $sql = "INSERT INTO departments (department_name) VALUES (:department_name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':department_name', $departmentName);
        $stmt->execute();

        return;
    }

    public function getAllDepartments(){

        $sql = "SELECT * FROM departments";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $departments?: [];
    }

    public function deleteDept($id){

        $sql = "DELETE FROM departments WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return;
    }


}