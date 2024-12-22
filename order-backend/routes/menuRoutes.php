<?php
// API route definitions for menus
require_once __DIR__ . '/../controllers/MenuController.php';
header('Content-Type: application/json');

$controller = new MenuController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Safely retrieve PATH_INFO or fallback to REQUEST_URI
$path = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
if (empty($path)) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = explode('/', trim(str_replace('/order-backend', '', $uri), '/')); // Adjust if needed
}

switch ($requestMethod) {
    case 'GET':
        if (!empty($path) && $path[0] === 'menus') {
            if (isset($path[1])) {
                echo json_encode($controller->getMenuById($path[1]));
            } else {
                echo json_encode($controller->getAllMenus());
            }
        }
        break;

    case 'POST':
        if (!empty($path) && $path[0] === 'menus') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($controller->createMenu($data));
        }
        break;

    case 'PUT':
        if (!empty($path) && $path[0] === 'menus' && isset($path[1])) {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode($controller->updateMenu($path[1], $data));
        }
        break;

    case 'DELETE':
        if (!empty($path) && $path[0] === 'menus' && isset($path[1])) {
            echo json_encode($controller->deleteMenu($path[1]));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['status' => 405, 'message' => 'Method Not Allowed']);
}
?>
