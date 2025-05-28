<?php

use App\Controllers\ContractController;
session_start();

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '../../../../../vendor/autoload.php';



if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $delete = (new ContractController)->deleteContract($id);

    if ($delete) {

        $_SESSION['notification'] = [
            'message' => 'Contract Deleted',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);

    }

    header("Location: " . $_SERVER['HTTP_REFERER']);

}

header("Location: " . $_SERVER['HTTP_REFERER']);