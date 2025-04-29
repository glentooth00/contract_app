<?php
session_start();
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php'; // corrected path

// Check if the contract_id and type are set via GET
if (isset($_GET['contract_id']) && isset($_GET['type'])) {
    // Sanitize the incoming GET parameters to avoid XSS or other security issues
    $contract_id = htmlspecialchars($_GET['contract_id']);
    $type = htmlspecialchars($_GET['type']);

    // Store the contract_id and type in the session for later use
    $_SESSION['contract_id'] = $contract_id;
    $_SESSION['contract_type'] = $type;

    // You can now check the type and redirect accordingly, if needed
    if ($type == TEMP_LIGHTING || $type == TRANS_RENT) {
        // This is where you would do some logic if you need to redirect or process the data
        header('Location: temp_light.php'); // redirect after storing the session variables
        exit(); // Don't forget to exit after header redirection
    } else {
        // Display the contract data or handle other types here
        // Include the logic for displaying the contract data or anything else here
    }
} else {
    // Handle the case where contract_id or type is not set in GET
    echo "Invalid request. Contract data not found.";
}
?>