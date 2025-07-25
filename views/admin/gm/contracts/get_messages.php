<?php

use App\Controllers\CommentController;
require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

header('Content-type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$contractId = $data['contract_id'] ?? '';

if(!$contractId){
    echo json_encode(['error' => 'Missing contract_id']);
    exit;
}

$getComments = (new CommentController)->getComments($contractId);

echo json_encode($getComments);

?>