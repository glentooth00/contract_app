<?php
session_start();

use App\Controllers\ContractController;

date_default_timezone_set('Asia/Manila'); // set this once at the top


require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

var_dump($_POST,'<br><br>');

$data = [
    'contract_end'=> $_POST['suspendDate'],
    'contract_id' => $_POST['contract_id']
];


$udpatedate = ( new ContractController )->updateEndDate($data);

if ($udpatedate) {

                $_SESSION['notification'] = [
                    'message' => 'Contract successfully extended!',
                    'type' => 'success'
                ];

                header("Location: " . $_SERVER['HTTP_REFERER']);
            }