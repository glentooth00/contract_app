<?php 
session_start();

require_once __DIR__ . '../../../../vendor/autoload.php';


use App\Controllers\ContractController;

$contractController = new ContractController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Upload the file
    $filePath = $contractController->uploadFile($_FILES["contract_file"]);

    if ($filePath) {
        // Prepare contract data
        $contractData = [
            'contract_name' => $_POST["contract_name"],
            'contract_type' => $_POST["contract_type"],
            'contract_start' => $_POST["contract_start"],
            'contract_end' => $_POST["contract_end"],
            'contract_file' => $filePath,
            'contract_status' => 'Active',
            'department_assigned' => $_POST["department_assigned"],
            'uploader_id' => $_POST['uploader_id'],
            'uploader_department' => $_POST['uploader_department'],
        ];

        // Save contract details
        if ($contractController->saveContract($contractData)) {
            
            $_SESSION['notification'] = [
                'message' => 'Contract successfully saved!',
                'type' => 'success'
            ];

            header('location:../contracts.php');

        } else {
            echo "<p>Error saving contract.</p>";
        }
    } else {
        echo "<p>Error uploading file.</p>";
    }
}