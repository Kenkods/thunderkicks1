<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/OrderController.php';

$conn = getConnection();
$orderController = new OrdersController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    $result = $orderController->updateOrderStatus($orderId, 'Completed');

    if ($result) {
        header("Location: /thunderkicks1/Thunderkick-advdb/admin/AdminDashboard.php?success=Order+marked+as+completed");
    } else {
        header("Location: /thunderkicks1/Thunderkick-advdb/admin/AdminDashboard.php?error=Failed+to+update+order");
    }
    exit();
}
