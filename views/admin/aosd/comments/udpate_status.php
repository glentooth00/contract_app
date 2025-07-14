<?php
use App\Controllers\CommentController;

session_start();
require_once __DIR__ . '/../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

if (isset($_GET['contract_id'])) {
    $updateStatus = [
        'contract_id' => $_GET['contract_id'],
        'status' => '0'
    ];

    $result = (new CommentController)->updateCommentStatus($updateStatus);

    if( $result){
   $_SESSION['notification'] = [
            'message' => 'updated',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    // Log result for debugging
    file_put_contents('log.txt', "Contract ID: {$_GET['contract_id']} | Status Update: " . ($result ? 'Success' : 'Fail') . PHP_EOL, FILE_APPEND);
}
