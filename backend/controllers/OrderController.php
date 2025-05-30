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

    public function getAllOrders()
    {
        $model = new OrderModel($this->conn);
        $recentOrders = $model->getAllOrders();
        return $recentOrders;
    }

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

    public function updateOrderStatus($orderId, $status)
    {
        $model = new OrderModel($this->conn);
        return $model->updateOrderStatus($orderId, $status);
    }



    public function getOrderHistory()
    {

        $model = new OrderModel($this->conn);
        $getHistory = $model->selectOrderitems();
        return $getHistory;
    }

    public function orderReceipt()
    {

        $model = new OrderModel($this->conn);
        $receipt = $model->viewReceipt();
        return $receipt;
    }

    public function getTotalSales()
    {
        $model = new OrderModel($this->conn);
        return $model->getTotalSales();
    }

    public function getTotalOrders()
    {
        $model = new OrderModel($this->conn);
        return $model->getTotalOrders();
    }

    public function getAdminNotifications()
    {
        $model = new OrderModel($this->conn);
        return $model->getAdminNotifications();
    }
}
