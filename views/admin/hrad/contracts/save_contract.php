<?php

use App\Controllers\EmploymentContractController;
use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '/../../../../vendor/autoload.php';

// Enable error reporting for full PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$contractController = new ContractController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Upload the file
    $filePath = $contractController->uploadFile($_FILES["contract_file"] ?? null);

    if ($filePath) {
        echo "<p>File uploaded successfully: $filePath</p>";

        // Prepare contract data
        $contractData = [
            'contract_name' => $_POST["contract_name"] ?? 'Missing contract_name',
            'contract_type' => $_POST["contract_type"] ?? 'Missing contract_type',
            'contract_start' => $_POST["contract_start"] ?? 'Missing contract_start',
            'contract_end' => $_POST["contract_end"] ?? 'Missing contract_end',
            'contract_file' => $filePath,
            'contract_status' => 'Active',
            'department_assigned' => $_POST["department_assigned"] ?? 'Missing department_assigned',
            'uploader_id' => $_POST['uploader_id'] ?? 'Missing uploader_id',
            'uploader_department' => $_POST['uploader_department'] ?? 'Missing uploader_department',
        ];

        echo "<pre>Contract Data:\n" . print_r($contractData, true) . "</pre>";

        // Save contract
        $contractSaved = $contractController->saveContract($contractData);

        if (!$contractSaved) {
            echo "<p>Error: Failed to save contract. Check ContractController::saveContract().</p>";
        }

        // Save employment history
        $employmentController = new EmploymentContractController();
        $employmentSaved = $employmentController->insertLatestData();

        if (!$employmentSaved) {
            echo "<p>Error: Failed to insert employment history. Check EmploymentContractController::insertLatestData().</p>";
        }

        if ($contractSaved && $employmentSaved) {
            $_SESSION['notification'] = [
                'message' => 'Contract successfully saved!',
                'type' => 'success'
            ];
            echo "<p>All operations successful. (Contract and Employment history saved.)</p>";
            // You can uncomment redirect once debugging is done
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

    } else {
        echo "<p>Error uploading file. Check ContractController::uploadFile().</p>";
        if (isset($_FILES["contract_file"])) {
            echo "<pre>File Upload Debug Info:\n" . print_r($_FILES["contract_file"], true) . "</pre>";
        } else {
            echo "<p>No file was uploaded (contract_file is missing).</p>";
        }
    }
} else {
    echo "<p>Invalid request method. Expected POST.</p>";
}
?>