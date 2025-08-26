<?php

use App\Controllers\ContractController;
use App\Controllers\NotificationController;
use App\Controllers\PendingDataController;

session_start();

$department = $_SESSION['department'] ?? null;
$role = $_SESSION['user_role'] ?? null;

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // $latestData = [
    //     'contract_id' => $_GET['id'], // Correct key for contract ID
    //     'contract_name' => $_GET['name'],
    //     'contract_start' => $_GET['start'], // match keys used in $latestData
    //     'contract_end' => $_GET['end'],
    //     'created_at' => date('Y-m-d H:i:s'),
    //     'updated_at' => date('Y-m-d H:i:s'),
    //     'contract_status' => 'Active',
    //     'status' => 1,
    //     'contract_type' => $_GET['type'],
    //     'uploader' => $_GET['uploader'],
    //     'uploader_id' => $_GET['uploader_id'],
    //     'uploader_department' => $_GET['uploader_dept'],
    //     'data_type' => 'Update',
    //     'updated_by' => $_GET['user'],
    //     'power_supply_long_start' => $_GET['powerSupplyLongStart1'],
    //     'power_supply_long_end' => $_GET['powerSupplyLongEnd1']

    // ];



    // $dataPendingUpdate = (new PendingDataController)->PendingInsert($latestData);

    // if ($dataPendingUpdate) {
    //     $_SESSION['notification'] = [
    //         'message' => 'Updated waiting for approval',
    //         'type' => 'success'
    //     ];

    //     header("location:" . $_SERVER['HTTP_REFERER']);
    //     exit();
    // }


    if($_GET['contract_type'] === PSC_LONG ){
    

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'],
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'],
            'name' => $_GET['name'],
            'uploader' => $_GET['uploader'],
            'uploader_id' => $_GET['uploader_id'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_type_update' => $_GET['contractType'],
            'contract_type' => $_GET['contract_type'],

        ];

        var_dump($contractData);

        // $insertPSLongData = ( new PendingDataController )->powerSupplyLong( $contractData);

        // if($insertPSLongData){

        //     $_SESSION['notification'] = [
        //         'message' => 'Contract updated , Waiting for approval',
        //         'type' => 'success'
        //     ];

        //     header("Location: " . $_SERVER['HTTP_REFERER']);

        // }
        

        

    }

    if($_GET['contract_type'] === PSC_SHORT ){
    
        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'],
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'],
            'name' => $_GET['name'],
            'uploader' => $_GET['uploader'],
            'uploader_id' => $_GET['uploader_id'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_start' => $_GET['psShortStart'],
            'contract_end' => $_GET['psShortEnd']
        ];

        $insertPSShortData = ( new PendingDataController )->powerSupplyShort( $contractData);

        if($insertPSShortData){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

        

    }

    if($_GET['contract_type'] === TRANS_RENT ){
    
        $startDate = date('Y-m-d', strtotime( $_GET['rentStart']));
        $endDate = date('Y-m-d', strtotime($_GET['rentEnd']));

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'] ?? 'null',
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'] ?? 'null',
            'name' => $_GET['name'],
            'uploader' => $_GET['uploader'],
            'uploader_id' => $_GET['uploader_id'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_type_update' => $_GET['contractType'],
            'rent_start' =>  $startDate,
            'rent_end' => $endDate

        ];

        // var_dump($contractData);


        $insertPSLongData = ( new PendingDataController )->powerSupplyLong( $contractData);


        if($insertPSLongData){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }
        

        

    }




}