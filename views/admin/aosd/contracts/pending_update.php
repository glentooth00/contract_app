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

    var_dump($_GET ,'<br><br>');

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


        $insertPSLongData = ( new PendingDataController )->powerSupplyLong( $contractData);

        if($insertPSLongData){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }
        

        

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
            'contract_end' => $_GET['psShortEnd'],
            'contract_type_update' => $_GET['contractType'],
            'contract_type' => $_GET['contract_type'],
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
    
        $startDate = date('Y-m-d', strtotime( $_GET['transRentStart']));
        $endDate = date('Y-m-d', strtotime($_GET['transRentEnd']));

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'] ?? 'null',
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'] ?? 'null',
            'name' => $_GET['name'],
            'uploader' => $_GET['uploadedBy'],
            'uploader_id' => $_GET['uploadId'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_type' => $_GET['contractType'],
            'rent_start' =>  $startDate,
            'rent_end' => $endDate
        ];

        var_dump($contractData);


        $insertPSLongData = ( new PendingDataController )->powerSupplyLong( $contractData);


        // if($insertPSLongData){

        //     $_SESSION['notification'] = [
        //         'message' => 'Contract updated , Waiting for approval',
        //         'type' => 'success'
        //     ];

        //     header("Location: " . $_SERVER['HTTP_REFERER']);

        // }
        

        

    }

    if($_GET['contract_type'] === GOODS ){
    
        $startDate = date('Y-m-d', strtotime( $_GET['rentStart'] ?? $_GET['goodsStart']));
        $endDate = date('Y-m-d', strtotime($_GET['rentEnd'] ?? $_GET['goodsEnd']));

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'] ?? 'null',
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'] ?? 'null',
            'name' => $_GET['name'],
            'uploader' => $_GET['uploadedBy'],
            'uploader_id' => $_GET['uploader_id'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_type_update' => $_GET['contractType'],
            'rent_start' =>  $startDate,
            'rent_end' => $endDate,
            'contract_type' => $_GET['contract_type'],
            'proc_mode' => $_GET['procurementMode'],
            'contract_start' => $_GET['goodsStart'],
            'contract_end' => $_GET['goodsEnd'],
            'total_cost' => $_GET['ttc'],
            'supplier' => $_GET['goodsSupplier']

        ];


        $GoodsUpdate = ( new PendingDataController )->goodsUpdate( $contractData);


        if($GoodsUpdate){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

    }

    if($_GET['contract_type'] === INFRA ){
    
        $startDate = date('Y-m-d', strtotime( $_GET['infra_start']));
        $endDate = date('Y-m-d', strtotime($_GET['infra_end']));

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'] ?? 'null',
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'] ?? 'null',
            'name' => $_GET['name'],
            'uploader' => $_GET['uploadedBy'],
            'uploader_id' => $_GET['uploadId'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_type' => $_GET['contract_type'],
            'proc_mode' => $_GET['procurementMode'],
            'contract_start' => $_GET['infra_start'],
            'contract_end' => $_GET['infra_end'],
            'total_cost' => trim(str_replace('₱', '', $_GET['contractPrice'])),
            'supplier' => $_GET['goodsSupplier']

        ];


        $infrasUpdate = ( new PendingDataController )->goodsUpdate( $contractData);


        if($infrasUpdate){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

    }

    if($_GET['contract_type'] === SACC ){
    
        $startDate = date('Y-m-d', strtotime( $_GET['rentStart'] ?? $_GET['saccDateStart']));
        $endDate = date('Y-m-d', strtotime($_GET['rentEnd'] ?? $_GET['saccDateEnd']));

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'] ?? 'null',
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'] ?? 'null',
            'name' => $_GET['name'],
            'uploader' => $_GET['uploadedBy'],
            'uploader_id' => $_GET['uploadId'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'contract_type_update' => $_GET['contractType'],
            'rent_start' =>  $startDate,
            'rent_end' => $endDate,
            'contract_type' => $_GET['contract_type'],
            'contract_start' =>$startDate,
            'contract_end' => $endDate,
            'total_cost' => trim(str_replace('₱', '', $_GET['ttc'])),
            'proc_mode' => $_GET['procurementMode']

        ];


        $GoodsUpdate = ( new PendingDataController )->SACCUpdate( $contractData);


        if($GoodsUpdate){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }
        
    }
    


    if($_GET['contract_type'] === TEMP_LIGHTING){

        $startDate = date('Y-m-d', strtotime( $_GET['tempLightStart']));
        $endDate = date('Y-m-d', strtotime($_GET['tempLightEnd']));

        $contractData = [
            'contract_id' => $_GET['id'],
            'powerSupplyLongStart1' => $_GET['powerSupplyLongStart1'] ?? 'null',
            'powerSupplyLongEnd1' => $_GET['powerSupplyLongEnd1'] ?? 'null',
            'name' => $_GET['name'],
            'account_no' => $_GET['account_no'],
            'uploader' => $_GET['uploadedBy'],
            'uploader_id' => $_GET['uploadId'],
            'uploader_dept' => $_GET['uploader_dept'],
            'data_type' => 'Update',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'uploader_department' => $_GET['uploader_dept'],
            'rent_start' =>  $startDate,
            'rent_end' => $endDate,
            'contract_type' => $_GET['contract_type'],
            'contract_start' => $startDate,
            'contract_end' => $endDate,
            'total_cost' => $_GET['ttc'],
            'procurement_mode' => $_GET['procurementMode'],
            'second_party' => $_GET['secondPart'],
            'tc_no' => $_GET['tcNumber'],
            'address' => $_GET['address']

        ];

        // var_dump($contractData);

        $temporaryLightingUpdate = ( new PendingDataController )->temporaryLightingUpdate( $contractData);

         if($temporaryLightingUpdate){

            $_SESSION['notification'] = [
                'message' => 'Contract updated , Waiting for approval',
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }

    }




}