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

    $test = $_SESSION['data'] ?? '';

    echo $test['username'];

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == $test['username']) {

        if ($password === $test['password']) {

            $_SESSION['is_logged_in'] = true;

            $get_id = $_SESSION['data'];
            $_SESSION['id'] = $get_id['id'] ?? null;

            // Set the department in its own session key
            $_SESSION['department'] = $_SESSION['data']['department'];

            switch ($_SESSION['department']) {
                case "Admin":
                    $_SESSION['department'] = $_SESSION['data']['department'];
                    header("location:views/admin/admin/index.php");
                    break;
                case "ISD-HRAD":
                    $_SESSION['department'] = $_SESSION['data']['department'];
                    header("location:views/admin/hrad/index.php");
                    break;
                case "ISD-MSD":
                    $_SESSION['department'] = $_SESSION['data']['department'];
                    header("location:views/admin/msd/index.php");
                    break;
                case "CITETD":
                    $_SESSION['department'] = $_SESSION['data']['department'];
                    header("location:views/admin/citetd/index.php");
                    break;
                case "BAC":
                    $_SESSION['department'] = $_SESSION['data']['department'];
                    header("location:views/admin/bac/index.php");
                    break;
                case "IASD":
                    $_SESSION['department'] = $_SESSION['data']['department'];
                    header("location:views/admin/iasd/index.php");
            }
        } else {

            // $_SESSION['password'] = 'Password is incorrect.';
            // header('location:index.php');
        }

    } else {

        // $_SESSION['username'] = 'Username is incorrect.';
        // header('location:index.php');
    }


}
