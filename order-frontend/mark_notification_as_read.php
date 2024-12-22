<?php
// Start the session
session_start();

// Check if the admin is logged in (optional, uncomment if needed)
// if (!isset($_SESSION['admin_id'])) {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
//     exit;
// }

// Include database connection
include_once 'api/config/database.php';

// Instantiate the database object
$database = new Database();
$db = $database->getConnection();

// Parse incoming JSON request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $notification_id = $data['id'];

    try {
        // Update the status of the notification to "read"
        $query = "UPDATE notifications SET status = 'read' WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $notification_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Notification marked as read.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Notification not found or already read.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request. Notification ID missing.']);
}
?>
