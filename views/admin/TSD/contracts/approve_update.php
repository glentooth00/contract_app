<?php

use App\Controllers\ContractHistoryController;
use App\Controllers\PendingDataController;
use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    var_dump($_POST);
    echo '<br>';
    echo '<br>';

    // if($_POST['contract_type'] === TEMP_LIGHTING){

    //     $updateData = [
    //         'contract_id' => $_POST['contract_id'],
    //         'uploader_department' => $_POST['uploader_department'],
    //         'contract_name' => $_POST['contract_name'],
    //         'contract_start' => $_POST['contract_start'],
    //         'contract_end' => $_POST['contract_end'],
    //         'contract_status' => 'Active',
    //         'address' => $_POST['address'],
    //     ];

    //     $updateSuccessful = (new ContractController)->managerUpdateTempLight($updateData);

    //         if( $updateSuccessful ){
    //            $deletePrevData = (new PendingDataController)->delete($updateData['contract_id']);

    //                 if( $deletePrevData ){

    //                         $updateContractData = (new ContractHistoryController )->updateHistoryTempLight($updateData);

    //                         if($updateContractData){

    //                         $_SESSION['notification'] = [
    //                         'message' => 'Update has been approved.',
    //                         'type' => 'success',
    //                         ];

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                         }

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                 }
    //         }

    // }

    // if($_POST['contract_type'] === TRANS_RENT){

    //     $updateData = [
    //         'contract_id' => $_POST['contract_id'],
    //         'uploader_department' => $_POST['uploader_department'],
    //         'contract_name' => $_POST['contract_name'],
    //         'rent_start' => $_POST['rent_start'],
    //         'rent_end' => $_POST['rent_end'],
    //         'contract_status' => 'Active',
    //         'address' => $_POST['address'],
    //         'tc_no' => $_POST['tc_no'],
    //         'account_no' => $_POST['account_no']
    //     ];


    //         $updateSuccessful = (new ContractController)->managerUpdateTransRent($updateData);


    //         if( $updateSuccessful ){

    //             $deletePrevData = (new PendingDataController)->delete($updateData['contract_id']);

    //                 if( $deletePrevData ){

    //                         $updateContractData = (new ContractHistoryController )->updateContractHistoryTransRent($updateData);

    //                         if($updateContractData){

    //                         $_SESSION['notification'] = [
    //                         'message' => 'Update has been approved.',
    //                         'type' => 'success',
    //                         ];

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                         }

    //                         $_SESSION['notification'] = [
    //                         'message' => 'Update has been approved.',
    //                         'type' => 'success',
    //                         ];

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                 }

    //                 $_SESSION['notification'] = [
    //                         'message' => 'Update has been approved.',
    //                         'type' => 'success',
    //                         ];

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;   

    //         }
    // }

    
    if($_POST['contract_type'] === GOODS){

        $updateData = [
            'contract_id' => $_POST['contract_id'],
            'uploader_department' => $_POST['uploader_department'],
            'contract_name' => $_POST['contract_name'],
            'contract_start' => $_POST['contract_start'],
            'contract_end' => $_POST['contract_end'],
            'contract_status' => 'Active',
            'supplier' => $_POST['supplier'] ?? '',
            'contractPrice' => trim(str_replace('₱', '', $_POST['total_cost'])),
            'contract_type' => $_POST['contract_type_update'] ?? $_POST['contract_type'],
        ];


        $updateSuccessful = (new ContractController)->managerUpdateGOODS($updateData);


            if( $updateSuccessful ){
               $deletePrevData = (new PendingDataController)->delete($updateData['contract_id']);

                    if( $deletePrevData ){

                            $updateContractData = (new ContractHistoryController )->updateHistoryTempLight($updateData);

                            if($updateContractData){

                            $_SESSION['notification'] = [
                            'message' => 'Update has been approved.',
                            'type' => 'success',
                            ];

                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit;

                            }

                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit;

                    }
            }

    }

    if($_POST['contract_type'] === INFRA){

        $startDate = date('Y-m-d', strtotime($_POST['contract_start']));
        $endDate = date('Y-m-d', strtotime($_POST['contract_end']));
        

        $updateData = [
            'contract_id' => $_POST['contract_id'],
            'uploader_department' => $_POST['uploader_department'],
            'contract_name' => $_POST['contract_name'],
            'contract_start' => $startDate,
            'contract_end' =>  $endDate,
            'contract_status' => 'Active', 
            'contractPrice' => trim(str_replace('₱', '', $_POST['total_cost'])),
            'contract_type' => $_POST['contract_type'],
            'procurementMode' => $_POST['proc_mode']
        ];


        $updateSuccessful = (new ContractController)-> managerUpdateINFRA($updateData);


            if( $updateSuccessful ){
               $deletePrevData = (new PendingDataController)->delete($updateData['contract_id']);

                    if( $deletePrevData ){

                            $updateContractData = (new ContractHistoryController )->updateHistoryTempLight($updateData);

                            if($updateContractData){

                            $_SESSION['notification'] = [
                            'message' => 'Update has been approved.',
                            'type' => 'success',
                            ];

                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit;

                            }

                            header("Location: " . $_SERVER['HTTP_REFERER']);
                            exit;

                    }
            }

    }

    // if($_POST['contract_type'] === EMP_CON){

    //     $updateData = [
    //         'contract_id' => $_POST['contract_id'],
    //         'uploader_department' => $_POST['uploader_department'],
    //         'contract_name' => $_POST['contract_name'],
    //         'contract_start' => $_POST['contract_start'],
    //         'contract_end' => $_POST['contract_end'],
    //         'contract_status' => 'Active', 
    //         'contract_type_update' => $_POST['contract_type_update']
    //     ];

    //     $updateSuccessful = (new ContractController)->managerUpdateTempLight($updateData);

    //     var_dump($updateSuccessful);

    //         if( $updateSuccessful ){
            
    //             $deletePrevData = (new PendingDataController)->delete($updateData['contract_id']);

    //                 if( $deletePrevData ){

    //                         $updateContractData = (new ContractHistoryController )->updateHistoryTempLight($updateData);

    //                         if($updateContractData){

    //                         $_SESSION['notification'] = [
    //                         'message' => 'Update has been approved.',
    //                         'type' => 'success',
    //                         ];

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                         }

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                 }
    //         }

    // }

    // if($_POST['contract_type'] === SACC){

    //     $startDate = date('Y-m-d', strtotime($_POST['contract_start']));
    //     $endDate = date('Y-m-d', strtotime($_POST['contract_end']));


    //     $updateData = [
    //         'contract_id' => $_POST['contract_id'],
    //         'uploader_department' => $_POST['uploader_department'],
    //         'contract_name' => $_POST['contract_name'],
    //         'contract_start' => $startDate,
    //         'contract_end' => $endDate,
    //         'contract_status' => 'Active',
    //         'contractPrice' => trim(str_replace('₱', '', $_POST['tcc'])),
    //         'contract_type_update' => $_POST['contract_type_update']
    //     ];

    //     var_dump($updateData);



    //     $updateSuccessful = (new ContractController)->managerUpdateSACC($updateData);


    //     if( $updateSuccessful ){
            
    //             $deletePrevData = (new PendingDataController)->delete($updateData['contract_id']);

    //                 if( $deletePrevData ){

    //                         $updateContractData = (new ContractHistoryController )->updateHistoryTempLight($updateData);

    //                         if($updateContractData){

    //                         $_SESSION['notification'] = [
    //                         'message' => 'Update has been approved.',
    //                         'type' => 'success',
    //                         ];

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                         }

    //                         header("Location: " . $_SERVER['HTTP_REFERER']);
    //                         exit;

    //                 }
    //         }


    // }

}

