<?php

use App\Controllers\ContractController;
use App\Controllers\ContractHistoryController;
use App\Controllers\EmploymentContractController;

require_once __DIR__ . '../../../../../vendor/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $updateData = [
        'id' => $_POST['contract_id'],
        'status' => 'Expired',
    ];

    var_dump($updateData);

    $updateExpiredEmploymentContract = (new ContractHistoryController)->udpateExpiredRentalContract($updateData);

    if ($updateExpiredEmploymentContract) {

        echo $id = $updateData['id'];

        $setExpire = (new ContractController)->setExpired($id);

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

    // header("Location: " . $_SERVER['HTTP_REFERER']);

}

// header("Location: " . $_SERVER['HTTP_REFERER']);



