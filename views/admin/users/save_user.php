<?php 
session_start();

$user_image = $_POST['user_image'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$user_role = $_POST['user_role'];
$department = $_POST['department'];
$gender = $_POST['gender'];

var_dump($user_image, $firstname, $middlename, $lastname, $user_role, $department, $gender);