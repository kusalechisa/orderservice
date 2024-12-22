// Handles API for fetching dashboard data (e.g., order stats, etc.)
<?php
require_once __DIR__ . '/../models/Order.php';

class DashboardController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function getOrderStats() {
        $orders = $this->orderModel->getAllOrders();
        $totalOrders = count($orders);
        $totalRevenue = array_sum(array_column($orders, 'total_price'));
        return [
            'status' => 200,
            'data' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue
            ]
        ];
    }
}
?>
