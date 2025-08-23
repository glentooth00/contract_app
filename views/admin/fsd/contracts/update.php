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
        'address' => $_GET['address'],
        'tc_no' => $_GET['tcNumber'],
        'account_no' => $_GET['account_no']
    ];


    $contractUpdate = (new PendingDataController )->PendingInsertTR($transRentData);


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
                     'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                      'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }


}

if ($_GET['type'] === TEMP_LIGHTING) {

    $EmpUpdate = [
        'contract_id' => $_GET['id'], // Correct key for contract ID
        'contract_name' => $_GET['name'],
        'contract_start' => $_GET['tempLightStart'],
        'contract_end' => $_GET['tempLightEnd'],
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
        'address' => $_GET['address']
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

                var_dump($updateContractHistory);

                if($updateContractHistory){


                $_SESSION['notification'] = [
                    'message' => 'Update for r.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                      'message' => 'Update successful. This record is now pending further review.',
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
        'created_at' => date('Y-m-d H:i:s'),
        'contract_status' => 'Active',
        'contract_type' => $_GET['contractType'],
        'status' => 1,
        'uploader_department' => 'ISD'
    ];


        $contractUpdate = (new PendingDataController )->employmentInsert($EmpUpdate);


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
                      'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                      'message' => 'Update successful. This record is now pending further review.',
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
        'implementing_dept' => $_GET['implementingDept'],
        'total_cost' => $price
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

                var_dump($currentData);

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistory($currentData);

             

                if($updateContractHistory){


                $_SESSION['notification'] = [
                      'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                      'message' => 'Update successful. This record is now pending further review.',
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
        'supplier' => $_GET['goodsSupplier'],
        'total_cost' => $price
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
                      'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                     'message' => 'Update successful. This record is now pending further review.',
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
        'total_cost' => $_GET['ttc']
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
                      'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

                }

                $_SESSION['notification'] = [
                      'message' => 'Update successful. This record is now pending further review.',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

            }


        }

    
}