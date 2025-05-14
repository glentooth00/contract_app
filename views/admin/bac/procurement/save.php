<?php

use App\Controllers\ProcurementController;
session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'procMode' => $_POST['procMode'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $procurementMode = (new ProcurementController)->storeProcMode($data);

    if ($procurementMode) {

        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Procurement Mode successfully Added',
            'title' => 'Procurement',
            'icon' => 'check-circle',
        ];

        header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));

    } else {

        $_SESSION['notification'] = [
            'type' => 'warning',
            'message' => 'Something went wrong',
            'title' => 'Contract Deletion',
            'icon' => 'check-circle',
        ];

        header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));
    }

}

header("Location: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'));