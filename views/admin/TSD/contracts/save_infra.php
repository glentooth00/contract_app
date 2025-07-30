<?php
session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

use App\Controllers\ContractController;

if ($_SERVER['REQUEST_METHOD'] === POST) {

    // Upload the file
    $filePath = (new ContractController)->uploadFile($_FILES["contract_file"] ?? null);

    $data = [
        'contract_file' => $filePath,
        'contract_name' => $_POST['contract_name'],
        'contractPrice' => $_POST['contractPrice'],
        'contract_start' => $_POST['contract_start'],
        'contract_type' => $_POST['contract_type'],
        'supplier' => $_POST['supplier'] ?? '',
        'contract_end' => $_POST['contract_end'],
        'procurementMode' => $_POST['procurementMode'],
        'implementing_dept' => $_POST['implementing_dept'],
        'contract_status' => 'Active',
        'uploader_id' => $_POST['uploader_id'],
        'uploader' => $_POST['uploader'],
        'uploader_department' => $_POST['uploader_department'],
    ];

    $saveContract = ( new ContractController )->storeContract( $data );

    if ( $saveContract ) {

        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Contract created successfully',
            'title' => 'contract creation',
            'icon' => 'check-circle',
        ];
        header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));
    }
    header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));
}
header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));

