<?php 

use App\Controllers\TempLightingController;

session_start();

require_once __DIR__ . '../../../../../vendor/autoload.php';

if (isset($_GET['id'])) {

    $delete = (new TempLightingController)->destroy($_GET['id']);
    
    if ($delete) {
        
        $_SESSION['notification'] = [
            'message' => 'Contract successfully deleted!',
            'type' => 'success'
        ];
    
    }

    // Only one redirect, always
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Fallback redirect if no ID
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
