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
    $message_id = $data['id'];

    try {
        // Delete the message from the database
        $query = "DELETE FROM messages WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $message_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Message deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Message not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request. Message ID missing.']);
}
?>
