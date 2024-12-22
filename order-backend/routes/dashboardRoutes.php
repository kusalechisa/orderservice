<?php
// API route definitions
require_once __DIR__ . '/../controllers/DashboardController.php';
header('Content-Type: application/json');

$controller = new DashboardController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI']; // Use REQUEST_URI instead of PATH_INFO
$path = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/')); // Parse the path from the URI

// Check if the method is GET and the route is 'dashboard'
if ($requestMethod === 'GET' && isset($path[0]) && $path[0] === 'dashboard') {
    echo json_encode($controller->getOrderStats());
} else {
    echo json_encode(['status' => 405, 'message' => 'Method Not Allowed']);
}
?>
