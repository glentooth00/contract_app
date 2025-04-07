<?php

use App\Controllers\DepartmentController;
session_start();

include_once '../../../vendor/autoload.php';

// $_POST['department_name'];

if($_REQUEST['POST'] = 'POST'){

    $addDept = new DepartmentController();
    $addDepartment = $addDept->addDepartment($_POST['department_name']);

    $_SESSION['notification'] = [
        'message' => 'Department Added successfully!',
        'type' => 'success'
    ];

    header('location:../department.php');
}

    // $_SESSION['notification'] = [
    //     'message' => 'Wat you doin step Bro?!',
    //     'type' => 'warning'
    // ];

header('location:../department.php');