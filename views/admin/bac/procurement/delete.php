<?php
session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

use App\Controllers\ProcurementController;

if (isset($_GET['id'])) {

    echo $id = $_GET['id']; // Make sure this is just an integer ID

    (new ProcurementController)->deleteMode($id);

    $_SESSION['notification'] = [
        'type' => 'success',
        'message' => 'Procurement Mode deleted successfully',
        'title' => 'Procurement Deletion',
        'icon' => 'check-circle',
    ];

    header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));

} else {
    echo 'data id is missing';
}
header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));

