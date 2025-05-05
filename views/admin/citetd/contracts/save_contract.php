<?php
use App\Controllers\EmploymentContractController;
use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $filePath = (new ContractController)->uploadFile($_FILES["contract_file"] ?? null);

    $dataValidation = [
        'contract_name' => $_POST['contract_name'],
        'contract_start' => $_POST['contract_start'],
        'contract_end' => $_POST['contract_end'],
        'contract_type' => $_POST['contract_type'],
        'contract_file' => $filePath,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'contract_status' => 'Active',
        'uploader_id' => $_POST['uploader_id'],
        'uploader_department' => $_POST['uploader_department'],
        'uploader' => $_POST['uploader'],
    ];

    $saveContract = (new ContractController)->savePowerSupplyContract($dataValidation);

    if ($saveContract) {

        $_SESSION['notification'] = [
            'message' => 'Power supply Contract successfully saved!',
            'type' => 'success'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

}