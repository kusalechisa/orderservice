<?php
// API route definitions
require_once __DIR__ . '/../controllers/UserController.php';
header('Content-Type: application/json');

$controller = new UserController();

// Get request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Safely retrieve the path from $_SERVER['REQUEST_URI']
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/order-backend'; // Ensure this matches your project folder name
$route = trim(str_replace($basePath, '', $uri), '/'); // Remove base path

// Split the route into parts
$path = explode('/', $route);

try {
    switch ($requestMethod) {
        case 'POST':
            // Handle POST requests for register and login
            if (!empty($path) && $path[0] === 'users') {
                if (isset($path[1]) && $path[1] === 'register') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo json_encode($controller->register($data));
                } elseif (isset($path[1]) && $path[1] === 'login') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo json_encode($controller->login($data));
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 400, 'message' => 'Invalid user action.']);
                }
            }
            break;

        case 'GET':
            // Handle GET request for profile
            if (!empty($path) && $path[0] === 'users' && isset($path[1]) && $path[1] === 'profile' && isset($path[2])) {
                echo json_encode($controller->getProfile($path[2]));
            } 
         elseif(!empty($path) && $path[0] === 'users') {
                http_response_code(400);
                echo json_encode($controller->getAllUsers($path[0]));
            }
            else {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Invalid user action or missing parameters.']);
            }
            break;

        default:
            http_response_code(405); // Method not allowed
            echo json_encode(['status' => 405, 'message' => 'Method Not Allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()]);
}
?>
