<?php
// Include the necessary UserController if it's not autoloaded
require_once __DIR__ . "../../../../../vendor/autoload.php"; // Update the path accordingly
session_start();

$userController = new App\Controllers\UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get other form data
    $firstname = $_POST['firstname'] ?? 'No Firstname';
    $middlename = $_POST['middlename'] ?? 'No Middlename';
    $lastname = $_POST['lastname'] ?? 'No Lastname';
    $user_role = $_POST['user_role'] ?? 'No User role';
    $user_type = $_POST['user_type'] ?? 'No User role';
    $department = $_POST['department'] ?? 'No Department';
    $gender = $_POST['gender'] ?? 'No Gender';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? ''; // You should hash this password before storing it
    $contractTypes = $_POST['contract_type'] ?? [];

    $jsonContractTypes = json_encode($contractTypes);

    // Collect all form data into an array
    $data = [
        'firstname' => $firstname,
        'middlename' => $middlename,
        'lastname' => $lastname,
        'user_role' => $user_role,
        'user_type' => $user_type,
        'department' => $department,
        'gender' => $gender,
        'username' => $username,
        'password' => $password,
        'contract_types' => $jsonContractTypes
    ];

    // Call the storeUser method to save the user data
    $result = $userController->storeUser($data);

    //Check if the user was created successfully
    if ($result) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'User Account created successfully',
            'title' => 'User Creation',
            'icon' => 'check-circle',
        ];
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    } else {
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

}


?>