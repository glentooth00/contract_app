<?php

use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $filePath = (new ContractController)->uploadRentalFile($_FILES["contract_file"] ?? null);

    $rentalData = [

        'contract_type' => $_POST['contract_type'],
        'contract_name' => $_POST['contract_name'],
        'contract_file' => $filePath,
        'tc_no' => $_POST['tc_no'],
        'rent_start' => $_POST['rent_start'],
        'rent_end' => $_POST['rent_end'],
        'contract_status' => 'Active',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'uploader_department' => $_POST['uploader_department'],
        'uploader_id' => $_POST['uploader_id'],
        'account_no' => $_POST['account_no'],
        'address' => $_POST['address'],

    ];

    // var_dump($rentalData);

    $saveContract = (new ContractController)->storeTransFormerRental($rentalData);

    if ($saveContract) {

        $_SESSION['notification'] = [
            'message' => 'Contract successfully saved!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    header("Location: " . $_SERVER['HTTP_REFERER']);

}

