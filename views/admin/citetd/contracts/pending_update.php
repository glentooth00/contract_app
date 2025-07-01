<?php

use App\Controllers\ContractController;
use App\Controllers\NotificationController;
use App\Controllers\PendingDataController;

session_start();

$department = $_SESSION['department'] ?? null;
$role = $_SESSION['user_role'] ?? null;

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

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
        'uploader_department' => $_GET['uploader_dept'],
        'data_type' => 'Update',

    ];

    // var_dump($latestData);

    $dataPendingUpdate = (new PendingDataController)->PendingInsert($latestData);

    if ($dataPendingUpdate) {
        $_SESSION['notification'] = [
            'message' => 'Updated waiting for approval',
            'type' => 'success'
        ];

        header("location:" . $_SERVER['HTTP_REFERER']);
        exit();
    }

}