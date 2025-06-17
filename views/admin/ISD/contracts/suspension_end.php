<?php
session_start();

use App\Controllers\ContractController;
use App\Controllers\SuspensionController;

date_default_timezone_set('Asia/Manila');

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';

$dataForUpdate = [
    'id' => $_GET['id'],
    'contract_status' => 'Active',
    'updated_at' => date('Y-m-d H:i:s'),
];


$i = (new ContractController)->updateStatus($dataForUpdate);

if ($i) {
    $_SESSION['notification'] = [
        'message' => "Contract successfully resumed! Remaining days: $remaining_days",
        'type' => 'success'
    ];

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}