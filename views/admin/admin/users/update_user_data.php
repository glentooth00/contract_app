<?php

use App\Controllers\UserController;
session_start();
require_once '../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    $data = [
        'firstname' => $_POST['firstname'],
        'middlename' => $_POST['middlename'],
        'lastname' => $_POST['lastname'],
        'gender' => $_POST['gender'],
        'department' => $_POST['department'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
    ];


    $id = ['id' => $_POST['id']];

    var_dump($id, $data);

    $updateUser = (new UserController)->updateUserData($data, $id);


    if (is_array($updateUser) && $updateUser['status'] === 'success') {
        $_SESSION['notification'] = [
            'message' => 'User Data updated successfully!',
            'type' => 'success'
        ];
    } else {
        $errorMessage = is_array($updateUser) ? $updateUser['message'] : $updateUser;

        $_SESSION['notification'] = [
            'message' => 'Failed to update user: ' . $errorMessage,
            'type' => 'error'
        ];
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

header("Location: " . $_SERVER['HTTP_REFERER']);
