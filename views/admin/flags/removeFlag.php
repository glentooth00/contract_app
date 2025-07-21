<?php
use App\Controllers\FlagController;
session_start();
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $removeFlag = [
        'contract_id' => $_POST['contract_id'],
        'status' => 0,
        'flag_type' => '',
    ];

    $FlagRemove = ( new FlagController )->updateStatus($removeFlag);

        if($FlagRemove){

              $_SESSION['notification'] = [
                'message' => 'Update has been approved',
                'type' => 'success',
            ];

            header("Location: " . $_SERVER['HTTP_REFERER']);

        }
        $_SESSION['notification'] = [
                'message' => 'Flag has been removed',
                'type' => 'success',
            ];

         header("Location: " . $_SERVER['HTTP_REFERER']);

}