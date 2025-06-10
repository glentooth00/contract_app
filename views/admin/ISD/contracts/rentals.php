<?php
use App\Controllers\EmploymentContractController;
use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if ($_POST['contract_type'] === TEMP_LIGHTING) {

        $filePath = (new ContractController)->uploadRentalFile($_FILES["contract_file"] ?? null);

        $contractDetails = [
            'TC_no' => $_POST['TC_no'],
            'contract_name' => $_POST['contract_name'],
            'contract_type' => $_POST['contract_type'],
            'status' => 'Active',
            'contract_start' => $_POST['contract_start'],
            'contract_end' => $_POST['contract_end'],
            'uploader_id' => $_POST['uploader_id'],
            'uploader' => $_POST['uploader'],
            'uploader_dept' => $_POST['uploader_dept'],
            'party_of_second_part' => $_POST['party_of_second_part'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'contract_file' => $filePath,
            'department_assigned' => 'ISD-MSD',
            'contrasct_status' => 'Active',
            'address' => $_POST['address'],
            'account_no' => $_POST['account_no'],

        ];

        var_dump($contractDetails);

        // $saveContract = (new ContractController)->createTempLightingContract($contractDetails);

        // if ($saveContract) {

        //     $_SESSION['notification'] = [
        //         'message' => 'Contract successfully saved!',
        //         'type' => 'success'
        //     ];

        //     header("Location: " . $_SERVER['HTTP_REFERER']);

        // }

        // header("Location: " . $_SERVER['HTTP_REFERER']);

    }
}
// header("Location: " . $_SERVER['HTTP_REFERER']);
