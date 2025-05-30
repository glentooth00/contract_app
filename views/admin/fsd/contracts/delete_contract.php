<?php
session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

use App\Controllers\ContractController;

if (isset($_GET['id'])) {

    echo $id = $_GET['id']; // Make sure this is just an integer ID

    (new ContractController)->deleteContract($id);

    $_SESSION['notification'] = [
        'type' => 'success',
        'message' => 'Contract deleted successfully',
        'title' => 'Contract Deletion',
        'icon' => 'check-circle',
    ];

    header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));

} else {
    echo 'data id is missing';
}
header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));

// echo $deleteId = $_GET['id'];
