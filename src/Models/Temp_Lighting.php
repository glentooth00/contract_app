<?php 
namespace App\Models;

use PDO;

class Temp_Lighting {

    protected $db;

    protected $table;

    public function __construct(PDO $db, $table){

        $this->db = $db;
        $this->table = $table;

    }

    public function getAll(){

        $sql = "SELECT * FROM $this->table ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
    }

    

}