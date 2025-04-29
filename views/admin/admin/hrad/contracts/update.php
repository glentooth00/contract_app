<?php

use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

// Prepare the latest contract data (make sure keys match what your method expects)
$latestData = [
    'id' => $_GET['id'], // Changed from 'contract_id'
    'contract_name' => $_GET['name'],
    'start' => $_GET['start'], // Changed from 'contract_start'
    'end' => $_GET['end'],     // Changed from 'contract_end'
    'department_assigned' => $_GET['dept'],
    'updated_at' => date('Y-m-d H:i:s') // Include current timestamp
];

// Call the controller method to perform update
$updateContract = (new ContractController)->updateContract($latestData) ?? false;

// Optional redirect back to previous page
if ($updateContract) {
    
    $_SESSION['notification'] = [
        'message' => 'Employment contract updated!',
        'type' => 'success'
    ];

    header("Location: " . $_SERVER['HTTP_REFERER']);
   
}
