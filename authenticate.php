<?php

session_start();

require_once __DIR__ . '../src/Config/constants.php';
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\UserController;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $auth = new UserController();

    $login = $auth->authenticate([
        'username' => $_POST['username'],
        'password' => $_POST['password'],
    ]);

    $test = $_SESSION['data'];


    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == $test['username']) {

        echo 'username is the same';


        if ($password == $test['password']) {

            $_SESSION['is_logged_in'];


            $get_id = $_SESSION['data'];

            $_SESSION['id'] = $get_id['id'] ?? null;

            $department = $_SESSION['data'];

            switch ($department['department']) {

                case "IT":
                    header("location:views/admin/IT/index.php");
                    break;

                case "ISD-HRAD":
                    header("location:views/admin/hrad/index.php");
                    break;

                case "ISD-MSD":
                    header("location:views/admin/msd/index.php");
                    break;

                case "CITETD":
                    header("location:views/admin/citetd/index.php");
                    break;
            }


            // header('location:views/admin/dashboard.php?');s


        } else {
            $_SESSION['password'] = 'Password is incorrect.';
            header('location:index.php');
        }
    } else {

        $_SESSION['username'] = 'Username is incorrect.';
        header('location:index.php');
    }


}
