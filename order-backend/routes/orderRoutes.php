<?php
// API route definitions for orders
require_once __DIR__ . '/../controllers/OrderController.php';
header('Content-Type: application/json');

$controller = new OrderController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Safely retrieve PATH_INFO or fallback to REQUEST_URI
$path = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
if (empty($path)) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = explode('/', trim(str_replace('/order-backend', '', $uri), '/')); // Adjust if needed
}

switch ($requestMethod) {
    case 'POST':
        if (!empty($path) && $path[0] === 'orders') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($controller->createOrder($data));
        }
        break;

    case 'GET':
        if (!empty($path) && $path[0] === 'orders') {
            if (isset($path[1])) {
                echo json_encode($controller->getOrdersByUserId($path[1]));
            } else {
                echo json_encode($controller->getAllOrders());
            }
        }
        break;

    case 'DELETE':
        if (!empty($path) && $path[0] === 'orders' && isset($path[1])) {
            $orderId = $path[1];
            echo json_encode($controller->deleteOrder($orderId));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 405, 'message' => 'Method Not Allowed']);
}
?>
