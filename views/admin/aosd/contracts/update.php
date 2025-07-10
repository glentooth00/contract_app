<?php

use App\Controllers\ContractController;
use App\Controllers\ContractHistoryController;
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
        'updated_at' => date('Y-m-d H:i:s'), // Add this to match bindParam
        'contract_status' => 'Active',
        'daysRemaining' => $_GET['daysRemaining'],
        'contract_type' => $_GET['type'],
    ];

    $type = $_GET['type'];

    $daysRemaining = $_GET['daysRemaining'];

    // var_dump($data);

    // Get contract type by ID
    $id = $data['id'];
    $getContractType = (new ContractController)->getContractbyId($id);

    $type = $getContractType['contract_type'];

    if ($getContractType['contract_type'] === TRANS_RENT) {

        $data1 = [
            'id' => $_GET['id'],
            'contract_status' => 'Active',
            'contract_name' => $_GET['name'],
            'rent_start' => $_GET['start'], // 'start' corresponds to rent_start in PHP
            'rent_end' => $_GET['end'],     // 'end' corresponds to contract_end in PHP
            'updated_at' => date('Y-m-d H:i:s') // Add this to match bindParam

        ];
        // var_dump($data1);
        $updateTransRent = (new ContractController)->updateTransRent($data1);

        if ($updateTransRent) {

            $daysRemaining;

            if ($daysRemaining > 0) {

                $i = [
                    'id' => $data['id'],
                    'status' => 'Active',
                ];

                $r = (new ContractHistoryController)->updatedExpired($i);

                $_SESSION['notification'] = [
                    'message' => $data['contract_name'] . ' updated!',
                    'type' => 'success'
                ];
                header("Location: " . $_SERVER['HTTP_REFERER']);

            } else {


                $i = [
                    'id' => $data['id'],
                    'status' => 'Expire',
                ];

                $r = (new ContractHistoryController)->updatedExpired($i);

                $_SESSION['notification'] = [
                    'message' => $data['contract_name'] . ' updated!',
                    'type' => 'success'
                ];
                header("Location: " . $_SERVER['HTTP_REFERER']);

            }
        }

    } elseif ($getContractType['contract_type'] === TEMP_LIGHTING) {

        $daysRemaining;

        if ($daysRemaining > 0) {

            $i = [
                'id' => $data['id'],
                'status' => 'Active',
            ];

            $r = (new ContractHistoryController)->updatedExpired($i);

            $_SESSION['notification'] = [
                'message' => $data['contract_name'] . ' updated!',
                'type' => 'success'
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);

        } else {


            $i = [
                'id' => $data['id'],
                'status' => 'Expire',
            ];

            $r = (new ContractHistoryController)->updatedExpired($i);

            $_SESSION['notification'] = [
                'message' => $data['contract_name'] . ' updated!',
                'type' => 'success'
            ];
            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


     if ($type === SACC) {

        $data = [
            'id' => $_GET['id'],
            'contract_name' => $_GET['name'],
            'contract_start' => $_GET['start'], // 'start' corresponds to rent_start in PHP
            'contract_end' => $_GET['end'],     // 'end' corresponds to contract_end in PHP
            'updated_at' => date('Y-m-d H:i:s'), // Add this to match bindParam
            'contract_status' => 'Active',
            'daysRemaining' => $_GET['daysRemaining'],
            'contract_type' => $_GET['type'],
        ];


        $id = $data['id'];

        $updateGoods = (new ContractController)->updateGoodsContract($data);

        if( $updateGoods){


            $getLatest =  (new ContractController)->getContractbyId($id);

            $updatedData = [
                'id' => $getLatest['id'],
                'contract_status' => 'Active',
                'contract_name' => $getLatest['contract_name'],
                'date_start' => $getLatest['contract_start'], // 'start' corresponds to rent_start in PHP
                'date_end' => $getLatest['contract_end'],     // 'end' corresponds to contract_end in PHP
                'updated_at' => date('Y-m-d H:i:s') // Add this to match bindParam
            ];

            $updateGoodsHistory = (new ContractHistoryController)->updateContractHistoryGoods($updatedData);

                if($updateGoodsHistory){

                    
                $_SESSION['notification'] = [
                    'message' => $data['contract_name'] . ' updated!',
                    'type' => 'success'
                ];
                header("Location: " . $_SERVER['HTTP_REFERER']);

                }


        }


        
        // $_SESSION['notification'] = [
        //     'message' => $data['contract_name'] . ' updated!',
        //     'type' => 'success'
        // ];
        // header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    if ($type === GOODS) {

        $data = [
            'id' => $_GET['id'],
            'contract_name' => $_GET['name'],
            'contract_start' => $_GET['start'], // 'start' corresponds to rent_start in PHP
            'contract_end' => $_GET['end'],     // 'end' corresponds to contract_end in PHP
            'updated_at' => date('Y-m-d H:i:s'), // Add this to match bindParam
            'contract_status' => 'Active',
            'daysRemaining' => $_GET['daysRemaining'],
            'contract_type' => $_GET['type'],
        ];

        // var_dump($data);


        $id = $data['id'];

        $updateGoods = (new ContractController)->updateGoodsContract($data);

        if( $updateGoods){


            $getLatest =  (new ContractController)->getContractbyId($id);

            $updatedData = [
                'id' => $getLatest['id'],
                'contract_status' => 'Active',
                'contract_name' => $getLatest['contract_name'],
                'date_start' => $getLatest['contract_start'], // 'start' corresponds to rent_start in PHP
                'date_end' => $getLatest['contract_end'],     // 'end' corresponds to contract_end in PHP
                'updated_at' => date('Y-m-d H:i:s') // Add this to match bindParam
            ];

            var_dump($updatedData);

            $updateGoodsHistory = (new ContractHistoryController)->updateContractHistoryGoods($updatedData);

                if($updateGoodsHistory){

                    
                $_SESSION['notification'] = [
                    'message' => $data['contract_name'] . ' updated!',
                    'type' => 'success'
                ];
                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => $data['contract_name'] . ' updated!',
                    'type' => 'success'
                ];
                header("Location: " . $_SERVER['HTTP_REFERER']);


        }

    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
}

header("Location: " . $_SERVER['HTTP_REFERER']);