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

    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Update query
    $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $db->prepare($query);

    // Bind values
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);

    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: user_list.php");
        exit();
    } else {
        echo "Error updating user.";
    }
} else {
    header("Location: user_list.php");
    exit();
}
?>
