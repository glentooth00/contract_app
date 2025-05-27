<?php

use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_GET['uploaderDept']) {
    $dept = $_GET['uploaderDept'];


    // Prepare the latest contract data (make sure keys match what your method expects)
    $latestData = [
        'contract_id' => $_GET['id'],
        'contract_name' => $_GET['name'],
        'start_date' => $_GET['start'],
        'end_date' => $_GET['end'],
        'user_dept' => $_GET['uploaderDept'],
        'action_status' => 'Pending update',
        'updated_at' => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_by' => $_GET['uploadedBy'],
        'status' => 1,

    ];

    // var_dump($latestData);

    // Call the controller method to perform update
    $updateContract = (new ContractController)->pendingUpdateContract($latestData) ?? false;

    // // Optional redirect back to previous page
    if ($updateContract) {

        $_SESSION['notification'] = [
            'message' => 'Contract update is waiting for approval.',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }


}






