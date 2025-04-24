<?php

use App\Controllers\UserController;
session_start(); // Ensure the session is started

require_once '../../../../vendor/autoload.php';

$id = $_POST['id'];

// Check if the form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Call the delete user function from the UserController
    $deleteUser = new UserController();
    $delete = $deleteUser->deleteUser($id);

        // Optionally handle failure
        $_SESSION['notification'] = [
            'message' => 'User deleted successfully.',
            'type' => 'success'
        ];

    // Redirect back to the users page after deletion
    header('Location: ../users.php');
    exit; // Make sure to exit after the redirect
}
?>
