<?php 

use App\Controllers\TestController;
session_start();
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php'; // corrected path

$contractId = [
        'id' => $_POST['id'],
];

$getContract = ( new TestController() )->getContracts($contractId);


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