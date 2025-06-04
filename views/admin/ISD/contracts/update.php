<?php

use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';


if ($_GET['type'] === TRANS_RENT) {

    $transrentData = [
        'id' => $_GET['id'], // Changed from 'contract_id'
        'contract_name' => $_GET['name'],
        'start' => $_GET['start'], // Changed from 'contract_start'
        'end' => $_GET['end'],     // Changed from 'contract_end'
        // 'department_assigned' => $_GET['dept'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active'
    ];

    $updateTransRent = (new ContractController)->updateTransRentContract($transrentData);

    if ($updateTransRent) {

        $_SESSION['notification'] = [
            'message' => 'Transformer Rental Contract has been successfully updated!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

}

if ($_GET['type'] === TEMP_LIGHTING) {

    $transrentData = [
        'id' => $_GET['id'], // Changed from 'contract_id'
        'contract_name' => $_GET['name'],
        'start' => $_GET['start'], // Changed from 'contract_start'
        'end' => $_GET['end'],     // Changed from 'contract_end'
        // 'department_assigned' => $_GET['dept'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active'
    ];

    $updateTransRent = (new ContractController)->updateContract($transrentData);

    if ($updateTransRent) {

        $_SESSION['notification'] = [
            'message' => 'Temporary Lighting Contract has been successfully updated!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

}

