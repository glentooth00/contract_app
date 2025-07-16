<?php

use App\Controllers\ContractController;
use App\Controllers\EmploymentContractController;

require_once __DIR__ . '../../../../../vendor/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $updateData = [
        'id' => $_POST['contract_id'],
        'status' => 'Employment contract ended',
    ];

    $updateExpiredEmploymentContract = (new EmploymentContractController)->udpateExpiredEmploymentContract($updateData);

    if ($updateExpiredEmploymentContract) {

        $updateContractData = [
            'id' => $_POST['contract_id'],
            'contract_status' => 'Expired',
        ];

        $udpateContractStatus = (new ContractController)->updateStatus($updateContractData);

        $_SESSION['notification'] = [
            'message' => 'Employment contract ended!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);


    } else {

        $_SESSION['notification'] = [
            'message' => 'Something went wrong!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    header("Location: " . $_SERVER['HTTP_REFERER']);

}

header("Location: " . $_SERVER['HTTP_REFERER']);



