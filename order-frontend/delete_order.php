<?php
// Database connection
include_once 'api/config/database.php';

if (isset($_GET['id'])) {
  $order_id = $_GET['id'];
} else {
  die('Order ID not provided');
}

// Instantiate the database and order object
$database = new Database();
$db = $database->getConnection();

// Delete the order
$query = "DELETE FROM orders WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $order_id);

if ($stmt->execute()) {
  header("Location: orders.php"); // Redirect to the orders list
  exit;
} else {
  echo "Error deleting order.";
}
