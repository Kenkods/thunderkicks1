<?php

class cartsModel {
    private $conn;

    public function __construct($conn) {  
        $this->conn = $conn;
    }

    public function addorUpdateCart($user_id, $shoe_id, $quantity, $price) {
    $stmt = $this->conn->prepare("CALL AddOrUpdateCartItem(?, ?, ?, ?)");
    $stmt->bind_param("iiid", $user_id, $shoe_id, $quantity, $price);
    if (!$stmt->execute()) {
        die("Procedure failed: " . $stmt->error);
    }
    $stmt->close();
    }
    public function getCarts($user_id){
        
        $query=" SELECT ci.*, s.*
                FROM cart_items ci
                JOIN cart c ON ci.cart_id = c.cart_id
                JOIN shoes s ON s.shoe_id = ci.shoe_id
                WHERE c.user_id = ? ";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $cartItems = $result->fetch_all(MYSQLI_ASSOC);
                return $cartItems;
    }
}

?>
