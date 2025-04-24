<?php 
namespace App\Models;

use PDO;

class Temp_Lighting {

    protected $db;

    protected $table;

    public function __construct(PDO $db){

        $this->db = $db;
        $this->table = 'temp_lighting';

    }

    public function getAll(){

        $sql = "SELECT * FROM $this->table ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
    }


    public function Delete($id){
      
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([ 'id' =>$id]);

        return $stmt->rowCount() > 0; 

    }



}