<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

class TempLightingController{

    private $db;

    public function __construct(){
        $this->db = Database::connect();
    }

    public function get($id){

       $sql = "SELECT * FROM temp_lighting WHERE id = :id";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);

       $stmt->execute();

       return $stmt->fetch(PDO::FETCH_ASSOC);



    }

}