<?php 
use App\Controllers\ContractController;
use App\Controllers\TransformerRentalController;

session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $filePath =  ( new ContractController )->uploadRentalFile($_FILES["contract_file"] ?? null);
    
    $rentalData  = [
        'contract_type' => $_POST['contract_type'],
        'customer_name' => $_POST['customer_name'],
        'contract_name' => $_POST['contract_name'],
        'contract_file' => $filePath,
        'tc_no' => $_POST['tc_no'],
        'rent_start' => $_POST['rent_start'],
        'rent_end' => $_POST['rent_end'],
        'uploader_id' => $_POST['uploader_id'],
        'uploader' => $_POST['uploader'],
        'uploader_dept' => $_POST['uploader_dept'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'status' => 'Active',
    ];

    $store = ( new TransformerRentalController )->insert($rentalData);

        if( $store ){

            
            $_SESSION['notification'] = [
                'message' => 'Transformer Rental contract successfully saved!',
                'type' => 'success'
            ];

                header("Location: " . $_SERVER['HTTP_REFERER']);

        }
    

        header("Location: " . $_SERVER['HTTP_REFERER']);

}

header("Location: " . $_SERVER['HTTP_REFERER']);