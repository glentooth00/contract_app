<?php

use App\Controllers\ContractController;
session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if (isset($_GET['id'])) {

    // Retrieve the parameters from the query string
    $data = [
        'id' => $_GET['id'],
        'contract_name' => $_GET['name'],
        'rent_start' => $_GET['start'], // 'start' corresponds to rent_start in PHP
        'rent_end' => $_GET['end'],     // 'end' corresponds to contract_end in PHP
        'updated_at' => date('Y-m-d H:i:s') // Add this to match bindParam
    ];

    // Get contract type by ID
    $id = $data['id'];
    $getContractType = (new ContractController)->getContractbyId($id);

    $type = $getContractType['contract_type'];

    if ($getContractType['contract_type'] === TRANS_RENT) {

        $data1 = [
            'id' => $_GET['id'],
            'contract_name' => $_GET['name'],
            'rent_start' => $_GET['start'], // 'start' corresponds to rent_start in PHP
            'rent_end' => $_GET['end'],     // 'end' corresponds to contract_end in PHP
            'updated_at' => date('Y-m-d H:i:s') // Add this to match bindParam
        ];

        $updateTransRent = (new ContractController)->updateTransRent($data1);
        if ($updateTransRent) {
            $_SESSION['notification'] = [
                'message' => $data['contract_name'] . ' updated!',
                'type' => 'success'
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

    } elseif ($getContractType['contract_type'] === TEMP_LIGHTING) {

        $data2 = [
            'id' => $_GET['id'],
            'contract_name' => $_GET['name'],
            'contract_start' => $_GET['start'], // 'start' corresponds to rent_start in PHP
            'contract_end' => $_GET['end'],     // 'end' corresponds to contract_end in PHP
            'updated_at' => date('Y-m-d H:i:s') // Add this to match bindParam
        ];

        $updateTransRent = (new ContractController)->updateTempLight($data2);
        if ($updateTransRent) {
            $_SESSION['notification'] = [
                'message' => $data['contract_name'] . ' updated!',
                'type' => 'success'
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
header("Location: " . $_SERVER['HTTP_REFERER']);