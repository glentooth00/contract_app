<?php
require_once __DIR__ . '../../../../../vendor/autoload.php';

use App\Controllers\ContractTypeController;

session_start();

if($_SERVER['REQUEST_METHOD'] === 'GET'){
   
    $contract_type_id = [
        'id' => $_GET['id'],
    ];

    $deleteContractType = (new ContractTypeController)->deleteContractType($contract_type_id);

    $_SESSION['notification'] = [
        'message' => 'Contract type Deleted!',
        'type' => 'success'
    ];
    
    header('location:../contract_types.php');

}

header('location:../contract_types.php');