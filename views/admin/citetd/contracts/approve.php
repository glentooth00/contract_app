<?php

use App\Controllers\PendingDataController;
session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

use App\Controllers\ContractController;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    $data = [
        'contract_name' => $_POST['contract_name'],
        'contract_type' => $_POST['contract_type'],
        'contract_start' => $_POST['contract_start'],
        'contract_end' => $_POST['contract_end'],
        'contract_file' => $_POST['contract_file'],
        'uploader_department' => $_POST['uploader_department'],
        'uploader' => $_POST['uploader'],
        'uploader_id' => $_POST['uploader_id'],
        'created_at' => $_POST['created_at'],
        'contract_status' => 'Active',
    ];

    var_dump($data);

    $saveContract = (new ContractController)->saveContract($data);

    if ($saveContract) {


        $id = $_POST['id'];

        $deletePending = (new PendingDataController)->delete($id);

        if ($deletePending) {

            $_SESSION['notification'] = [
                'message' => 'New Contract has been approved!',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

        $_SESSION['notification'] = [
            'message' => 'New Contract has been approved!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);


    }

}