<?php

use App\Controllers\CommentController;
use App\Controllers\NotificationController;

require_once __DIR__ . '../../../../../src/Config/constants.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

$controller = new CommentController();
$count = $controller->getNotifications();

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
?>