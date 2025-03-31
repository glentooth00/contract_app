<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\UserController;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $auth = new UserController();

    $login = $auth->authenticate([
        'username' => $_POST['username'],
        'password' => $_POST['password'],
    ]);

    $test =  $_SESSION['data'];

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == $test['username']) {

        echo 'username is the same';


        if ($password == $test['password']) {

            $_SESSION['is_logged_in'];

            header('location:views/admin/dashboard.php');


        } else {
            $_SESSION['password'] = 'Password is incorrect.';
            header('location:index.php');
        }
    } else {

        $_SESSION['username'] = 'Username is incorrect.';
        header('location:index.php');
    }


}
