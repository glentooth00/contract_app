<?php 

use App\Controllers\EmploymentContractController;
use App\Controllers\ContractController;

session_start();

require_once __DIR__ . '../../../../vendor/autoload.php';

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

        // Save contract
        $contractSaved = $contractController->saveContract($contractData);

        // Prepare employment history data
        $employmentData = [
            'contract_name' => $_POST["contract_name"],
            'contract_type' => $_POST["contract_type"],
            'status' => 'Active',
            'contract_file' => $filePath,
            'date_start' => $_POST["contract_start"],
            'date_end' => $_POST["contract_end"],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Save employment history
        $employmentSaved = (new EmploymentContractController)->storeEmploymentRecord($employmentData);

        if ($contractSaved && $employmentSaved) {
            $_SESSION['notification'] = [
                'message' => 'Contract successfully saved!',
                'type' => 'success'
            ];
            header('Location: ../contracts.php');
            exit;
        } else {
            echo "<p>Error saving contract or employment history.</p>";
        }

    } else {
        echo "<p>Error uploading file.</p>";
    }
}
