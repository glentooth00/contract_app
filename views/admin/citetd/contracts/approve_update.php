<?php

use App\Controllers\ContractHistoryController;
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
        'contract_type' => $_POST['contract_type_update'] ?? ''
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

                $id = $updateData['contract_id'];

                $getContract = ( new ContractController )->getContractbyId($id);

                $contractHistoryData = [
                        'id' => $getContract['id'],
                        'contract_name' => $getContract['contract_name'],
                        'contract_start' => $getContract['contract_start'],
                        'contract_end' => $getContract['contract_end'],
                        'updated_at' => date('Y-m-d H:i:s'),
                ];
                

                $updateConHistory = ( new ContractHistoryController )->updateContractHistoryPowerSupply($contractHistoryData);

                if($updateConHistory){

                    $_SESSION['notification'] = [
                    'message' => 'Update has been approved.',
                    'type' => 'success',
                    ];

                }

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
