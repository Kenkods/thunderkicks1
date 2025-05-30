<?php

require_once __DIR__ . '/../model/orderModel.php';

class OrdersController
{
    private $conn;
    private $model;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->model = new OrderModel($conn); // Capitalized to match PSR naming
    }

    /**
     * Get all orders (for admin panel).
     */
    public function getAllOrders()
    {
        return $this->model->getAllOrders();
    }

    /**
     * Get detailed items for a specific order.
     */
    public function getOrderItems($orderId)
    {
        return $this->model->getOrderItems($orderId);
    }

    /**
     * Get limited recent orders (e.g., latest 5).
     */
    public function getRecentOrders()
    {
        return $this->model->getRecentOrders();
    }
}
