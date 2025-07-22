<?php

use App\Controllers\ContractController;
use App\Controllers\ContractHistoryController;
use App\Controllers\PendingDataController;

session_start();


require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';


if ($_GET['type'] === TRANS_RENT) {

    $transRentData = [
        'contract_id' => $_GET['id'], // Correct key for contract ID
        'contract_name' => $_GET['name'],
        'contract_start' => $_GET['transRentStart'],
        'contract_end' => $_GET['transRentEnd'],
        'created_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d'),
        'contract_status' => 'Active',
        'status' => 1,
        'contract_type' => $_GET['type'],
        'uploader' => $_GET['uploadedBy'],
        'uploader_id' => $_GET['uploadId'],
        'uploader_department' => $_GET['uploader_dept'],
        'data_type' => 'Update',
        'updated_by' => $_GET['updatedBy'],
    ];


    $contractUpdate = (new PendingDataController )->PendingInsert($transRentData);

        if ($contractUpdate) {

            $id = $transRentData['contract_id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['rent_start'],
                    'date_end' => $getCurrenData['rent_end'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'contractPrice' =>  $price ?? ''
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateHistoryTransRent($currentData);

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Infrastructure Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => 'Infrastructure Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }


}

if ($_GET['type'] === TEMP_LIGHTING) {

    $transrentData = [
        'id' => $_GET['id'], // Changed from 'contract_id'
        'contract_name' => $_GET['name'],
        'start' => $_GET['start'], // Changed from 'contract_start'
        'end' => $_GET['end'],     // Changed from 'contract_end'
        // 'department_assigned' => $_GET['dept'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active'
    ];

    $updateTransRent = (new ContractController)->updateContract($transrentData);

    if ($updateTransRent) {

            $id = $transrentData['id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['contract_start'],
                    'date_end' => $getCurrenData['contract_end'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistory($currentData);

                var_dump($updateContractHistory);

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Employment Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => 'Employment Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }

}

if($_GET['type'] === EMP_CON){

    $EmpUpdate = [
        'id' => $_GET['id'],
        'contract_name' => $_GET['name'],
        'start' => $_GET['EmpStart'],
        'end' => $_GET['ConEmpEnd'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active'
    ];
    
        $contractUpdate = (new ContractController)->updateContract($EmpUpdate);

        if ($contractUpdate) {

            $id = $EmpUpdate['id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['contract_start'],
                    'date_end' => $getCurrenData['contract_end'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistory($currentData);

                var_dump($updateContractHistory);

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Employment Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => 'Employment Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }

    
}

//update done
if($_GET['type'] === INFRA){

    $price = str_replace('₱', '',$_GET['ttc']);

    $EmpUpdate = [
        'contract_id' => $_GET['id'], // Correct key for contract ID
        'contract_name' => $_GET['name'],
        'contract_start' => $_GET['infraStart'],
        'contract_end' => $_GET['infraEnd'],
        'created_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d'),
        'contract_status' => 'Active',
        'status' => 1,
        'contract_type' => $_GET['type'],
        'uploader' => $_GET['uploadedBy'],
        'uploader_id' => $_GET['uploadId'],
        'uploader_department' => $_GET['uploader_dept'],
        'data_type' => 'Update',
        'updated_by' => $_GET['updatedBy'],
    ];

        $contractUpdate = (new PendingDataController )->PendingInsert($EmpUpdate);

        if ($contractUpdate) {

            $id = $EmpUpdate['contract_id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['contract_start'],
                    'date_end' => $getCurrenData['contract_end'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'contractPrice' =>  $price
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistory($currentData);

             

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Infrastructure Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => 'Infrastructure Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }

    
}

//update done
if($_GET['type'] === GOODS){

    $price = str_replace('₱', '',$_GET['ttc']);

    $EmpUpdate = [
        'contract_id' => $_GET['id'], // Correct key for contract ID
        'contract_name' => $_GET['name'],
        'contract_start' => $_GET['goodsStart'],
        'contract_end' => $_GET['goodsEnd'],
        'created_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d'),
        'contract_status' => 'Active',
        'status' => 1,
        'contract_type' => $_GET['type'],
        'uploader' => $_GET['uploadedBy'],
        'uploader_id' => $_GET['uploadId'],
        'uploader_department' => $_GET['uploader_dept'],
        'data_type' => 'Update',
        'updated_by' => $_GET['updatedBy'],


    ];

    
    $contractUpdate = (new PendingDataController )->PendingInsert($EmpUpdate);

        if ($contractUpdate) {

            $id = $EmpUpdate['contract_id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['contract_start'],
                    'date_end' => $getCurrenData['contract_end'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistory($currentData);

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Goods Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => 'Goods Contract has been successfully updated!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }
}


//update done
if($_GET['type'] === SACC){

    $price = str_replace('₱', '',$_GET['ttc']);

    $EmpUpdate = [
        'contract_id' => $_GET['id'], // Correct key for contract ID
        'contract_name' => $_GET['name'],
        'contract_start' => $_GET['saccDateStart'],
        'contract_end' => $_GET['saccDateEnd'],
        'created_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d'),
        'contract_status' => 'Active',
        'status' => 1,
        'contract_type' => $_GET['type'],
        'uploader' => $_GET['uploadedBy'],
        'uploader_id' => $_GET['uploadId'],
        'uploader_department' => $_GET['uploader_dept'],
        'data_type' => 'Update',
        'updated_by' => $_GET['updatedBy'],


    ];

    
        $contractUpdate = (new PendingDataController )->PendingInsert($EmpUpdate);

        if ($contractUpdate) {

            $id = $EmpUpdate['contract_id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['contract_start'],
                    'date_end' => $getCurrenData['contract_end'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistory($currentData);

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Contract update is for approval!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                    'message' => 'Contract update is for approval!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }

    
}