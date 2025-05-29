<?php

class cartsModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addorUpdateCart($user_id, $shoe_id, $quantity, $price, $size)
    {
        $stmt = $this->conn->prepare("CALL AddOrUpdateCartItem(?, ?, ?, ?, ?)");
        $stmt->bind_param("iiids", $user_id, $shoe_id, $quantity, $price, $size); // Fix: last param is string for size
        if (!$stmt->execute()) {
            die("Procedure failed: " . $stmt->error);
        }
        $stmt->close();
    }

    public function getsize($shoe_id)
    {
        $query = "SELECT * FROM size WHERE shoe_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $shoe_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCarts($user_id)
    {
        $query = "
        SELECT ci.*, s.*, sz.size AS selected_size
        FROM cart_items ci
        JOIN cart c ON ci.cart_id = c.cart_id
        JOIN shoes s ON s.shoe_id = ci.shoe_id
        JOIN sizes sz ON sz.size_id = ci.size_id
        WHERE c.user_id = ?
    ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }


      public function transferCartToOrder($userId, $selectedIds)
    {
        $stmt = $this->conn->prepare("CALL TransferCartToOrder(?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("is", $userId, $selectedIds);

        if (!$stmt->execute()) {
            die("Execution failed: " . $stmt->error);
        }

        $stmt->close();
    }


}
