<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Temp_Lighting;
use PDO;

class TempLightingController{

    protected $db;

    protected $model;

    public function __construct(){
        $this->db = Database::connect();
        $this->model = new Temp_Lighting($this->db);
    }

    public function get($id){

       $sql = "SELECT * FROM temp_lighting WHERE id = :id";
       $stmt = $this->db->prepare($sql);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);

       $stmt->execute();

       return $stmt->fetch(PDO::FETCH_ASSOC);

    }


    public function destroy($id){
        $delete = $this->model->Delete($id);
        return;
    }

}