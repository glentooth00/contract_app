<?php
session_start();
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php'; // corrected path


echo $contract_id = $_GET['contract_id'];
$type = $_GET['type'];

if ($type == TEMP_LIGHTING | $type == TRANS_RENT) {
    echo $type;
}