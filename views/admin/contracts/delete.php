<?php
session_start();

require_once __DIR__ . '../../../../vendor/autoload.php';

use App\Controllers\ContractController;

// Create an instance of ContractController
$contractController = new ContractController();

 if(isset($_GET['id'])){

    $id = $_GET['id']; // Make sure this is just an integer ID
    $contractController = new ContractController();
    $contractController->deleteContract($id);

    $_SESSION['notification'] = [
        'type' => 'success',
        'message' => 'Contract deleted successfully',
    ];
    
    header('Location: ../contracts.php');

 }else{
    echo 'data id is missing';
 }

    // echo $deleteId = $_GET['id'];
