<?php

namespace App\Controllers;

use App\Models\TransformerRental;
use PDO;


class TransformerRentalController{


    public function insert($data){

        return $insert = ( new TransformerRental)->insert('transformer_rental', $data);

    }

    public function getTransRentById($id){

        return $get = ( new TransformerRental )->getById($id);

        

    }


}