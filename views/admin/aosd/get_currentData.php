<?php 

use App\Controllers\TestController;
session_start();
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php'; // corrected path

$contractId = [
        'contract_id' => $_POST['contract_id'],
];

$getContract = ( new TestController() )->getCurrent($contractId);


if($getContract){
    echo json_encode([
        'success' => true,
        'data' => $getContract
    ]);
}else{
    echo json_encode([
        'success' => false,
        'message' => 'No contract found'
    ]);
}