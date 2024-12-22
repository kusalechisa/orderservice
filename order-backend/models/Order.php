<?php
// Model for order data (interacts with DB)
require_once __DIR__ . '/../config/database.php';

class Order {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function createOrder($userId, $menuItems, $totalPrice) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO orders (user_id, menu_items, total_price) VALUES (:user_id, :menu_items, :total_price)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':menu_items', $menuItems); // JSON-encoded array of menu items
            $stmt->bindParam(':total_price', $totalPrice);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getOrdersByUserId($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllOrders() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM orders");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function deleteOrder($orderId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM orders WHERE id = :id");
            $stmt->bindParam(':id', $orderId);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
