<?php
require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractTypeController;

session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
   
    $contract_data = [
        'contract_type' => $_POST['contract_type'],
        'contract_ert' => $_POST['contract_ert'],
        'contract_duration' => $_POST['contract_duration'],
        'contract_id' => $_POST['contract_id'],
    ];

    $saveContractType = (new ContractTypeController)->insertContractType($contract_data);

    $_SESSION['notification'] = [
        'message' => 'Contract type saved!',
        'type' => 'success'
    ];
    
    header('location:../contract_types.php');

}

// if(){
    
// }



header('location:../contract_types.php');