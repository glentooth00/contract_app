<?php

use App\Controllers\ContractController;
use App\Controllers\ContractHistoryController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';


if ($_GET['type'] === TRANS_RENT) {

    $transrentData = [
        'id' => $_GET['id'], // Changed from 'contract_id'
        'contract_name' => $_GET['name'],
        'start' => $_GET['start'], // Changed from 'contract_start'
        'end' => $_GET['end'],     // Changed from 'contract_end'
        // 'department_assigned' => $_GET['dept'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active'
    ];

    $updateTransRent = (new ContractController)->updateTransRentContract($transrentData);

    if ($updateTransRent) {

            $id = $transrentData['id'];

            $getCurrenData = ( new ContractController  )->getContractByIdUpdated($id);

            if(!empty($getCurrenData)){

                $currentData = [
                    'id' => $getCurrenData['id'],
                    'contract_name' => $getCurrenData['contract_name'],
                    'date_start' => $getCurrenData['rent_start'],
                    'date_end' => $getCurrenData['rent_end'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $updateContractHistory = ( new ContractHistoryController )->updateContractHistoryTransRent($currentData);

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

if($_GET['type'] === INFRA){

    $price = str_replace('₱', '',$_GET['ttc']);

    $EmpUpdate = [
        'id' => $_GET['id'],
        'contract_name' => $_GET['name'],
        'start' => $_GET['EmpStart'],
        'end' => $_GET['ConEmpEnd'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active',
        'contractPrice' => $price
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

if($_GET['type'] === GOODS){

    $price = str_replace('₱', '',$_GET['ttc']);

    $EmpUpdate = [
        'id' => $_GET['id'],
        'contract_name' => $_GET['name'],
        'start' => $_GET['EmpStart'],
        'end' => $_GET['ConEmpEnd'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active',
        'contractPrice' => $price
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

if($_GET['type'] === SACC){

    $price = str_replace('₱', '',$_GET['ttc']);

    $EmpUpdate = [
        'id' => $_GET['id'],
        'contract_name' => $_GET['name'],
        'start' => $_GET['EmpStart'],
        'end' => $_GET['ConEmpEnd'],
        'updated_at' => date('Y-m-d H:i:s'),// Include current timestamp
        'contract_status' => 'Active',
        'contractPrice' => $price
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