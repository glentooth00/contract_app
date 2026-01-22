<?php
use App\Controllers\EmploymentContractController;
use App\Controllers\ContractController;
use App\Controllers\PendingDataController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $filePath = (new ContractController)->uploadFile($_FILES["contract_file"] ?? null);

    echo $role = $_SESSION['user_role'] ?? null;

    if ($role === CHIEF) {

        $dataValidation = [
            'contract_name' => $_POST['contract_name'],
            'contract_start' => $_POST['contract_start'],
            'contract_end' => $_POST['contract_end'],
            'contract_type' => $_POST['contract_type'],
            'contract_file' => $filePath,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'contract_status' => 'Active' ?? null,
            'uploader_id' => $_POST['uploader_id'],
            'uploader_dep`artment' => $_POST['uploader_department'],
            'uploader' => $_POST['uploader'],
            'approval_status' => 'Pending',
            'data_type' => 'New',
            'status' => 1,
            'updated_by' => null
        ];

        var_dump($dataValidation);

        // $saveContract = (new PendingDataController)->savePendingData($dataValidation);


        // if ($saveContract) {

        //     $_SESSION['notification'] = [
        //         'message' => 'Power supply Contract successfully added waiting for approval!',
        //         'type' => 'success'
        //     ];
        //     header("Location: " . $_SERVER['HTTP_REFERER']);
        //     exit;
        // }

        // $_SESSION['notification'] = [
        //     'message' => 'Power supply Contract successfully added waiting for approval!',
        //     'type' => 'success'
        // ];
        // header("Location: " . $_SERVER['HTTP_REFERER']);
        // exit;


    }

    if ($role === 'Staff') {

        $dataValidation = [
            'contract_name' => $_POST['contract_name'],
            'contract_start' => $_POST['contract_start'],
            'contract_end' => $_POST['contract_end'],
            'contract_type' => $_POST['contract_type'],
            'contract_file' => $filePath,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'contract_status' => 'Active' ?? null,
            'uploader_id' => $_POST['uploader_id'],
            'uploader_department' => $_POST['uploader_department'],
            'uploader' => $_POST['uploader'],
            'approval_status' => 'Pending',
            'data_type' => 'New',
            'status' => 1,
            'updated_by' => null
        ];

        var_dump($dataValidation);

        $saveContract = (new PendingDataController)->savePendingData($dataValidation);


        if ($saveContract) {

            $_SESSION['notification'] = [
                'message' => 'Power supply Contract successfully added waiting for approval!',
                'type' => 'success'
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $_SESSION['notification'] = [
            'message' => 'Power supply Contract successfully added waiting for approval!',
            'type' => 'success'
        ];
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;


    }
    // $_SESSION['notification'] = [
    //     'message' => 'Power supply Contract successfully added waiting for approval!',
    //     'type' => 'success'
    // ];
    // header("Location: " . $_SERVER['HTTP_REFERER']);
    // exit;



}