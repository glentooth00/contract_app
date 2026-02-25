<?php

use App\Controllers\UserController;
session_start();
require_once __DIR__ . '../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../vendor/autoload.php';
use App\Controllers\ChangePasswordController;


    $ChangePasswordData = [
        'id' => $_POST['id'],
        'new_password' => $_POST['new_password']
    ];

    $changePass = ( new ChangePasswordController )->updatePassword($ChangePasswordData);

    if($changePass){

        $updateStatus = 'completed';

        $updateChangePass = ( new UserController )->updateRequest($ChangePasswordData['id'], $updateStatus );

            if($updateChangePass){
                header('location:reset_password.php');
            }

    }else{
        echo false;
    }


exit;
