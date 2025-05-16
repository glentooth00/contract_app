<?php 
use App\Controllers\ContractController;
use App\Controllers\EmploymentContractController;

session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $filePath = (new ContractController)->uploadFile($_FILES["contract_file"]);

    $latestContract = [
        'contract_id' => $_POST['contract_id'],
        'contract_name' => $_POST['contract_name'],
        'contract_start' => $_POST['contract_start'],
        'contract_end' => $_POST['contract_end'],
        'contract_type' => $_POST['contract_type'],
        'department_assigned' => $_POST['department_assigned'],
        'contract_status' => 'Active',
        'contract_file' => $filePath,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $renew = (new ContractController)->renewContract($latestContract);

    if($renew){

        $getLastUpdated = (new EmploymentContractController )->insertLastupdatedData($latestContract);

        if($getLastUpdated){
            
             $_SESSION['notification'] = [
                'message' => 'Contract successfully renewed!',
                'type' => 'success'
            ];
            
            header("Location: " . $_SERVER['HTTP_REFERER']);

        }else{

            $_SESSION['notification'] = [
                'message' => 'Something went wrong when renewing contract!',
                'type' => 'warning'
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);

}