<?php

use App\Controllers\UserController;
session_start();

include_once __DIR__ . "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $checkUsername = (new UserController())->checkUsername($_POST['username']);

    if ($checkUsername) {

        $_SESSION['notification'] = [
            'message' => 'Username is valid',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        $_SESSION['notification'] = [
            'message' => 'Username is not found',
            'type' => 'danger'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

}
