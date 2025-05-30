<?php

require_once __DIR__ . '/../model/OrderModel.php';
require_once __DIR__ . '/../config/db.php';
$conn = getConnection();
class OrdersController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get all orders (for admin panel).
     */
    public function getAllOrders()
    {
        $model = new OrderModel($this->conn);
        $recentOrders = $model->getAllOrders();
        return $recentOrders;
    }

    /**
     * Get detailed items for a specific order.
     */
    public function getOrderItems($orderId)
    {
        $model = new OrderModel($this->conn);
        $getOrderItems = $model->getOrderItems($orderId);
        return  $getOrderItems;
    }


    public function getRecentOrders()
    {
        $model = new OrderModel($this->conn);
        $getRecentOrders = $model->getRecentOrders();
        return $getRecentOrders;
    }
}
