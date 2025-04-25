<?php 
namespace App\Models;

use App\Config\Database;

use PDO;
class TransformerRental{

    protected $conn;

    public function __construct(){
        $this->conn = Database::connect();
    }

    public function insert($tableName, $data) {

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO $tableName ($columns) VALUES ($placeholders)";

        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($values);
        } catch (\PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
            return false;
        }
    }

    public function getById($id){

        $sql = "SELECT * FROM transformer_rental WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;


    }

}

