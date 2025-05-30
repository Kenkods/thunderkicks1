<?php

class OrderModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllOrders()
    {
        $sql = "
            SELECT o.order_id, o.created_at, u.username, SUM(ci.quantity * ci.price) as total_amount
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            JOIN order_items ci ON ci.order_id = o.order_id
            GROUP BY o.order_id
            ORDER BY o.created_at DESC
        ";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderItems($order_id)
    {
        $stmt = $this->conn->prepare("
            SELECT oi.*, s.name AS shoe_name, sz.size
            FROM order_items oi
            JOIN shoes s ON s.shoe_id = oi.shoe_id
            JOIN sizes sz ON sz.size_id = oi.size_id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentOrders()
    {
        $query = "
        SELECT oi.*, s.name, s.shoe_img,o.status,u.username,u.user_id
        FROM order_items oi
        JOIN shoes s ON oi.shoe_id = s.shoe_id
        JOIN orders o On oi.order_id=o.order_id
        JOIN users u ON o.user_id=u.user_id
        ORDER BY oi.created_at DESC
        LIMIT 10
    ";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOrderStatus($order_id, $status)
    {
        $stmt = $this->conn->prepare("
        UPDATE orders 
        SET status = ?, updated_at = NOW() 
        WHERE order_id = ?
    ");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }

    public function selectOrderitems()
    {

        $query = "SELECT oi.*, o.*
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.order_id
            WHERE o.user_id = ? 
            ORDER BY o.order_date DESC
            ";
        $user_id = $_SESSION['users']['user_id'];
        $stmt = $this->conn->query($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function viewReceipt()
    {
        $user_id = $_SESSION['user']['user_id'];
        $receipt = $this->conn->prepare("SELECT * FROM viewReceipt WHERE user_id = ?
    ORDER BY created_at desc ");
        $receipt->bind_param("i", $user_id);
        $receipt->execute();
        $result = $receipt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getTotalSales()
    {
        $stmt = $this->conn->prepare("
        SELECT SUM(total_amount) as total_sales 
        FROM orders 
        WHERE status = 'Completed'
    ");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total_sales'] ?? 0;
    }

    public function getTotalOrders()
    {
        $stmt = $this->conn->prepare("
        SELECT COUNT(*) as total_orders 
        FROM orders
    ");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total_orders'] ?? 0;
    }

    public function getAdminNotifications()
    {
        $query = "
            SELECT notif_id, description, created_at
            FROM notification
            WHERE user_id IS NULL -- Notifications for all admins
            ORDER BY created_at DESC
            LIMIT 5
        ";
        $result = $this->conn->query($query);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // Log the error or handle it appropriately
            error_log("Error fetching admin notifications: " . $this->conn->error);
            return [];
        }
    }
}
