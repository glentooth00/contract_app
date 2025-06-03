<?php

use App\Controllers\PendingDataController;
use App\Controllers\ContractController;

session_start();
error_reporting(E_ALL);
require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $updateData = [
        'id' => $_POST['id'],
        'contract_id' => $_POST['contract_id'],
        'uploader_department' => $_POST['uploader_department'],
        'contract_name' => $_POST['contract_name'],
        'contract_start' => $_POST['contract_start'],
        'contract_end' => $_POST['contract_end'],
    ];

    $updateSuccessful = (new ContractController)->managerUpdate($updateData);

    if ($updateSuccessful) {
        $deletePrevData = (new PendingDataController)->delete($updateData['id']);

        if ($deletePrevData) {
            $_SESSION['notification'] = [
                'message' => 'Update has been approved',
                'type' => 'success',
            ];
        } else {
            $_SESSION['notification'] = [
                'message' => 'Update succeeded approved.',
                'type' => 'success',
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'message' => 'Failed to update contract data.',
            'type' => 'error',
        ];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
