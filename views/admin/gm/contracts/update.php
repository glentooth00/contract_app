<?php

use App\Controllers\ContractController;
use App\Controllers\NotificationController;
use App\Controllers\PendingDataController;

session_start();

echo $department = $_SESSION['department'] ?? null;
echo $role = $_SESSION['user_role'] ?? null;

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';



if ($role === CHIEF) {

    // Prepare the latest contract data (make sure keys match what your method expects)
    $latestData = [
        'contract_id' => $_GET['id'], // Correct key for contract ID
        'contract_name' => $_GET['name'],
        'contract_start' => $_GET['start'], // match keys used in $latestData
        'contract_end' => $_GET['end'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'contract_status' => 'Active',
        'status' => 1,
        'contract_type' => $_GET['type'],
        'uploader' => $_GET['uploader'],
        'uploader_id' => $_GET['uploader_id'],
        'uploader_department' => $_GET['uploader_dept']

    ];

    $saveUpdateData = (new PendingDataController)->PendingInsert($latestData);

    if ($saveUpdateData) {

        $_SESSION['notification'] = [
            'message' => 'Contract update waiting for approved!',
            'type' => 'success'
        ];

        header("Location:../list.php ");

    }

}





// var_dump($latestData);

// Call the controller method to perform update
// $updateContract = (new ContractController)->updateContractByAdmin($latestData) ?? false;

// Optional redirect back to previous page
// if ($updateContract) {

// $latestData2 = [
//     'id' => $_POST['id'],
//     // 'contract_name' => $_POST['contract_name'],
//     // 'start_date' => $_POST['contract_start'],
//     // 'end_date' => $_POST['contract_end'],
//     // 'department_assigned' => $_POST['department_assigned'] ?? null, // add this
//     // 'contract_status' => $_POST['contract_status'] ?? null,         // add this
//     // 'status' => '0',
//     // 'updated_at' => date('Y-m-d H:i:s')
// ];

// var_dump($latestData2);


// $update = (new NotificationController)->delete($latestData2);


// $_SESSION['notification'] = [
//     'message' => 'Contract update approved!',
//     'type' => 'success'
// ];

// header("Location:../list.php ");

// }
