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

        // echo 'username is the same';


        if ($password == $test['password']) {

            // $_SESSION['is_logged_in'];


                $get_data = $_SESSION['data'];

                $department = $get_data['department'];

                $userId = $get_data['id'];

                switch($department){

                    case 'ISD-MSD':
                        $_SESSION['department'] =  $department;
                        header('location:views/admin/isdmsd/index.php');
                        break;

                    case 'CITETD':
                        $_SESSION['department'] =  $department;
                        header('location:views/admin/contracts.php');
                        break;   
                        
                    case 'ISD-HRAD':
                        $_SESSION['department'] =  $department;
                        header('location:views/admin/contracts.php');
                        break;
                }

                // $encoded =  base64_encode( $userId);


                // header('location:views/admin/dashboard.php?user='. $encoded);


        } else {
            $_SESSION['password'] = 'Password is incorrect.';
            header('location:index.php');
        }
    } else {

        $_SESSION['username'] = 'Username is incorrect.';
        header('location:index.php');
    }


}
