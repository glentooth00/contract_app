<?php

use App\Controllers\ContractController;
use App\Controllers\NotificationController;

session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

// Prepare the latest contract data (make sure keys match what your method expects)
$latestData = [
    'contract_id' => $_POST['contract_id'], // Correct key for contract ID
    'contract_name' => $_POST['contract_name'],
    'contract_start' => $_POST['contract_start'], // match keys used in $latestData
    'contract_end' => $_POST['contract_end'],
    'action_status' => 'Updated' ?? null,
    'updated_at' => date('Y-m-d H:i:s'),
    'contract_status' => 'Active'

];


// var_dump($latestData);

// Call the controller method to perform update
$updateContract = (new ContractController)->updateContractByAdmin($latestData) ?? false;

// Optional redirect back to previous page
if ($updateContract) {

    $latestData2 = [
        'id' => $_POST['id'],
        // 'contract_name' => $_POST['contract_name'],
        // 'start_date' => $_POST['contract_start'],
        // 'end_date' => $_POST['contract_end'],
        // 'department_assigned' => $_POST['department_assigned'] ?? null, // add this
        // 'contract_status' => $_POST['contract_status'] ?? null,         // add this
        // 'status' => '0',
        // 'updated_at' => date('Y-m-d H:i:s')
    ];

    // var_dump($latestData2);


    $update = (new NotificationController)->delete($latestData2);


    $_SESSION['notification'] = [
        'message' => 'Contract update approved!',
        'type' => 'success'
    ];

    header("Location:../list.php ");

}
