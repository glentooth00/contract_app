<?php

use App\Controllers\ContractHistoryController;
use App\Controllers\SuspensionController;
session_start();
use App\Controllers\ContractController;

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if (isset($_POST['terminate'])) {

    $filePath = (new ContractController)->uploadTerminationFile($_FILES["document"] ?? null);

    $data = [
        'document' => $filePath,
        'contract_id' => $_POST['contract_id'],
        'contract_status' => 'Terminated',
    ];

    $contract_id = $data['contract_id'];

    $updateSet = (new SuspensionController)->updateDocumentColumn($data);

    if ($updateSet) {

        $data2 = [
            'contract_id' => $data['contract_id'],
            'contract_status' => 'Contract Terminated'
        ];

        $updateContractTable = (new ContractController)->terminateContract($data2);

        if ($updateContractTable) {

            $data3 = [
                'contract_id' => $data['contract_id'],
                'status' => 'Contract Terminated'
            ];

            $updateContractHistory = (new ContractHistoryController)->updateTerminateStatus($data3);

            if ($updateContractHistory) {

                $_SESSION['notification'] = [
                    'message' => 'Contract has been Terminated!',
                    'type' => 'warning'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }

    }

}