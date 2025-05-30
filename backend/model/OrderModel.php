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
            SELECT o.order_id, o.order_date, u.username, SUM(ci.quantity * ci.price) as total_amount
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            JOIN order_items ci ON ci.order_id = o.order_id
            GROUP BY o.order_id
            ORDER BY o.order_date DESC
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
        SELECT oi.*, s.name, s.shoe_img,o.status
        FROM order_items oi
        JOIN shoes s ON oi.shoe_id = s.shoe_id
        JOIN orders o On oi.order_id=o.order_id
        ORDER BY oi.created_at DESC
        LIMIT 10
    ";

        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
