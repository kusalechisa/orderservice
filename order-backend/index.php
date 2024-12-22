<?php
// Entry point for API routes
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Extract the URI path and remove base path if needed
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/order-backend'; // Update this if your project folder has a different name
$route = trim(str_replace($basePath, '', $uri), '/'); // Remove base path and extra slashes

// Debugging: Log the extracted route
error_log("Extracted Route: $route");

// Split the route into parts (e.g., users/register becomes ['users', 'register'])
$routeParts = explode('/', $route);

// Route matching based on first component (e.g., 'users', 'menus')
try {
    switch ($routeParts[0]) {
        case 'users':
            require_once __DIR__ . '/routes/userRoutes.php';
            break;
        case 'menus':
            require_once __DIR__ . '/routes/menuRoutes.php';
            break;
        case 'orders':
            require_once __DIR__ . '/routes/orderRoutes.php';
            break;
        case 'dashboard':
            require_once __DIR__ . '/routes/dashboardRoutes.php';
            break;
        case '':
            // Welcome message if no route is provided
            echo json_encode(['status' => 200, 'message' => 'Welcome to the Order Backend API!']);
            break;
        default:
            // Handle route not found
            http_response_code(404);
            echo json_encode(['status' => 404, 'message' => 'Route not found.']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()]);
}
?>
