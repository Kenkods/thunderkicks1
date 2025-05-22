<?php
class CardsModel {

    private $conn;

    public function __construct($conn) {  
        $this->conn = $conn;
    }

    public function getShoes($brand_name) {
       $query ="
            SELECT s.name, s.price, s.shoe_img, sz.size, sz.stock
            FROM shoes s
            JOIN brand b ON s.brand_id = b.brand_id
            JOIN sizes sz ON s.shoe_id = sz.shoe_id
            WHERE b.brand_name = ?
            ";

        
      
        
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

    public function getCategory($category_name, $limit, $offset){
      $query ="SELECT s.name, s.price, s.shoe_img, GROUP_CONCAT(CONCAT(sz.size, ':', sz.stock) SEPARATOR ', ') AS sizes
                FROM shoes s
                JOIN category c ON s.cat_id = c.cat_id
                JOIN sizes sz ON s.shoe_id = sz.shoe_id
                WHERE c.category_name = ?
                GROUP BY s.shoe_id
                LIMIT ? OFFSET ?";

            

        $stmt=$this->conn->prepare($query);
        if(!$stmt){
            die("Prepare Failed: " . $this->conn->error);

        }
        $stmt->bind_param("sii", $category_name,$limit, $offset);
        $stmt->execute();
        $result=$stmt->get_result();
        
        $categories = [];
        if($result->num_rows> 0){
            while($row=$result->fetch_assoc()){
                $categories[]=$row;
            }

        }
        $stmt->close();
        return $categories;
    }
    
       public function filterProducts($brand, $category, $size, $type) {
    $sql = "SELECT shoes.*, brand.brand_name, category.category_name, type.shoe_type, sizes.*
            FROM shoes
            INNER JOIN brand ON shoes.brand_id = brand.brand_id
            INNER JOIN category ON shoes.cat_id = category.cat_id
             INNER JOIN type ON shoes.type_id = type.type_id
             INNER JOIN sizes ON shoes.shoe_id = sizes.shoe_id
            WHERE 1";

    $params = [];
    
    if ($brand) {
        $sql .= " AND brand.brand_name = ?";
        $params[] = $brand;
    }
    if ($category) {
        $sql .= " AND category.category_name = ?";
        $params[] = $category;
    }
    if ($size) {
        $sql .= " AND sizes.size = ?";
        $params[] = $size;
    }
    if($type){
        $sql .= " AND type.shoe_type = ?";
        $params[] = $type;

    }

  
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
    die("Prepare failed: " . $this->conn->error);
}
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    return $products;
}

public function getShoeID($shoe_id){
    $sql = "SELECT * FROM shoes WHERE shoe_id=?";
    $stmt = $this->conn->prepare($sql);

     if (!$stmt) {
    die("Prepare failed: " . $this->conn->error);
}
    $stmt->bind_param("s", $shoe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $shoes = [];
    while ($row = $result->fetch_assoc()){
        $shoes[] = $row;
    }
    return $shoes;




}


}
?>
