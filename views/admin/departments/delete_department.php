<?php

use App\Controllers\DepartmentController;
session_start();

include_once '../../../vendor/autoload.php';

$id = $_GET['id'];

if($_REQUEST['POST'] = 'POST'){

    $Dept = new DepartmentController();
    $deleteDepartment = $Dept->deleteDept($id);

    $_SESSION['notification'] = [
        'message' => 'Department Deleted successfully!',
        'type' => 'success'
    ];

    header('location:../department.php');
}

    // $_SESSION['notification'] = [
    //     'message' => 'Wat you doin step Bro?!',
    //     'type' => 'warning'
    // ];

header('location:../department.php');