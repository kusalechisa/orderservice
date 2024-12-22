<?php
require_once __DIR__ . '/../models/Order.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function createOrder($data) {
        if (empty($data['user_id']) || empty($data['menu_items']) || empty($data['total_price'])) {
            return ['status' => 400, 'message' => 'All fields are required.'];
        }

        if (!is_array($data['menu_items']) || !is_numeric($data['total_price']) || $data['total_price'] <= 0) {
            return ['status' => 400, 'message' => 'Invalid data provided.'];
        }

        $menuItemsJson = json_encode($data['menu_items']);
        $result = $this->orderModel->createOrder($data['user_id'], $menuItemsJson, $data['total_price']);
        if ($result) {
            return ['status' => 201, 'message' => 'Order created successfully.'];
        }
        return ['status' => 500, 'message' => 'Failed to create order.'];
    }

    public function getOrdersByUserId($userId) {
        $orders = $this->orderModel->getOrdersByUserId($userId);
        if ($orders) {
            return ['status' => 200, 'orders' => $orders];
        }
        return ['status' => 404, 'message' => 'No orders found for the user.'];
    }

    public function getAllOrders() {
        $orders = $this->orderModel->getAllOrders();
        return ['status' => 200, 'orders' => $orders];
    }

    public function deleteOrder($orderId) {
        if (empty($orderId)) {
            return ['status' => 400, 'message' => 'Order ID is required.'];
        }

        $result = $this->orderModel->deleteOrder($orderId);
        if ($result) {
            return ['status' => 200, 'message' => 'Order deleted successfully.'];
        }

        return ['status' => 500, 'message' => 'Failed to delete the order.'];
    }
}
?>
