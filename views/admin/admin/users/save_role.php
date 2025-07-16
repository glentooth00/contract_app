<?php

use App\Controllers\UserRoleController;
session_start();

require_once '../../../../vendor/autoload.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $role = $_POST['user_role'];

    $saveRole = (new UserRoleController)->saveRole($role);

    if ($saveRole) {

        $_SESSION['notification'] = [
            'message' => 'Role successfully Added.',
            'type' => 'success'
        ];

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));

    }
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));
}

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/'));