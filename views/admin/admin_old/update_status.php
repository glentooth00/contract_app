<?php 

session_start();
$contract_status = $_SESSION['contract_status'];
$contract_id = $_SESSION['contract_id'];
// Use the values as needed
echo $contract_status;
echo $contract_id;