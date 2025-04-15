<?php 

use App\Controllers\EmploymentContractController;

require_once __DIR__ . '../../../vendor/autoload.php';

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $updateData = [
        'id' => $_POST['contract_id'],
        'status' => 'Employmeny contract ended',
    ];


    
    $updateExpiredEmploymentContract = (new EmploymentContractController)->udpateExpiredEmploymentContract($updateData);
    
    if($updateExpiredEmploymentContract){

          $_SESSION['notification'] = [
            'message' => 'Employment contract ended!',
            'type' => 'success'
        ];

           header("Location: " . $_SERVER['HTTP_REFERER']);

    }else{
          header("Location: " . $_SERVER['HTTP_REFERER']);
    }

       header("Location: " . $_SERVER['HTTP_REFERER']);


    }




