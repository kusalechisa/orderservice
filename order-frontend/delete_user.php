<?php
session_start();

// Check if admin is logged in
// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include_once 'api/config/database.php';
    $database = new Database();
    $db = $database->getConnection();

    // Retrieve user ID from the POST request
    $id = $_POST['id'];

    // Delete query
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);

    // Bind value
    $stmt->bindParam(':id', $id);

    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: user_list.php");
        exit();
    } else {
        alert("Error deleting user.");
    }
} else {
    header("Location: user_list.php");
    exit();
}
?>
