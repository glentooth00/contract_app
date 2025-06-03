<?php

use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

// Prepare the latest contract data (make sure keys match what your method expects)
$latestData = [
    'contract_id' => $_GET['id'], // this must match ':contract_id'
    'contract_name' => $_GET['name'],
    'start' => $_GET['start'],
    'end' => $_GET['end'],
    'department_assigned' => $_GET['dept'],
    'contract_status' => 'Active',
    'updated_at' => date('Y-m-d H:i:s')
];


// var_dump($latestData);

//Call the controller method to perform update
$updateContract = (new ContractController)->updateContract1($latestData) ?? false;

// Optional redirect back to previous page
if ($updateContract) {

    $_SESSION['notification'] = [
        'message' => 'Successfully updated contract!',
        'type' => 'success'
    ];

    header("Location: " . $_SERVER['HTTP_REFERER']);

}
