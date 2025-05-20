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

    $test = $_SESSION['data'] ?? null;

    if (is_array($test)) {

        $userNAme = $test['username'];
        $passWord = $test['password'];

        $username = $_POST['username'];
        $password = $_POST['password'];


        if ($username === $userNAme) {


            if ($password === $passWord) {


                $_SESSION['is_logged_in'] = true;

                $get_id = $_SESSION['data'];
                $_SESSION['id'] = $get_id['id'] ?? null;

                // Set the department in its own session key
                $_SESSION['department'] = $_SESSION['data']['department'];

                switch ($_SESSION['department']) {

                    case "IT":
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

                $_SESSION['password'] = 'password is incorrect.';
                header('location:index.php');
            }


        } else {

            $_SESSION['username'] = 'username is incorrect.';
            header('location:index.php');
        }

    } else {
        // // Optional: handle the error, like redirect or show message
        $_SESSION['credentials'] = 'Incorrect Credentials.';
        header('location:index.php');


    }

    // $_SESSION['username'] = 'Incorrect username.';
    // header('location:index.php');


}
// header('location:index.php');
// $_SESSION['username'] = 'Username is incorrect.';
// header('location:index.php');

