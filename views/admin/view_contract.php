<?php
session_start();

use App\Controllers\ContractController;

require_once __DIR__ . '../../../vendor/autoload.php';

$contact_id = $_GET['contract_id'];

$contract =  new ContractController();

// $getContract = $contract->getContractbyId($contact_id);

$page_title = 'View Contract | '. $getContract['contract_name'];

include_once '../../views/layouts/includes/header.php'; 

?>