<?php

use App\Controllers\UserController;
session_start();

include_once __DIR__ . "../../vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $checkUsername = (new UserController())->checkUsername($_POST['username']);

    if ($checkUsername) {


        if ($checkUsername == true) {

            $data = [
                'user_id' => $checkUsername['id'],
                'username' => $checkUsername['username'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $i = (new UserController)->changePass($data);



            $_SESSION['notification'] = [
                'message' => 'Change password request sent successfully',
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

    } else {


        $_SESSION['notification'] = [
            'message' => 'Username is not found',
            'type' => 'danger'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

}
