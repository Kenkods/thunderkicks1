<?php
class CardsModel {

    private $conn;

    public function __construct($conn) {  
        $this->conn = $conn;
    }

    public function getShoes($brand_name) {
        $query = "SELECT s.name, s.price, s.stock, s.shoe_img FROM shoes s JOIN brand b ON s.brand_id = b.brand_id WHERE b.brand_name=?";
        
      
        
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("s", $brand_name);
        $stmt->execute();

        $result = $stmt->get_result();

        $cards = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cards[] = $row;
            }
        }
        $stmt->close();
        return $cards;
    }
}
?>
