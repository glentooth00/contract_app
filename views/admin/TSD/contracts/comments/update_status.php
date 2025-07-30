<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
use App\Controllers\CommentController;
session_start();
// require_once __DIR__ . '/../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php'; // corrected path


if (isset($_GET['contract_id'])) {
    $contractId = $_GET['contract_id'];

                    $updateStatus = [
                        'contract_id' => $_GET['contract_id'],
                        'status' => '0'
                    ];

                    $updateCommentStatus = (new CommentController)->updateCommentStatus($updateStatus);
                // Do your database logic here
                // Example:
                echo "Contract ID $contractId status updated!";
            } else {
                echo "Invalid request.";
            }
            ?>
