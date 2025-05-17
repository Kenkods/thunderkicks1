<?php
class CardsModel {

    private $conn;

    public function __construct($conn) {  
        $this->conn = $conn;
    }

    public function getShoes($brand_name) {
        $query = "SELECT s.name, s.price, s.stock, s.size, s.shoe_img FROM shoes s JOIN brand b ON s.brand_id = b.brand_id WHERE b.brand_name=?";
        
      
        
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
        $query="SELECT s.shoe_id, s.name, s.price, s.stock, s.size, s.shoe_img FROM shoes s JOIN category c ON s.cat_id =c.cat_id WHERE c.category_name=?
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
    $sql = "SELECT shoes.*, brand.brand_name, category.category_name, type.shoe_type
            FROM shoes
            INNER JOIN brand ON shoes.brand_id = brand.brand_id
            INNER JOIN category ON shoes.cat_id = category.cat_id
             INNER JOIN type ON shoes.type_id = type.type_id
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
        $sql .= " AND shoes.size = ?";
        $params[] = $size;
    }
    if($type){
        $sql .= " AND type.shoe_type = ?";
        $params[] = $type;

    }

    $stmt = $this->conn->prepare($sql);
    
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


}
?>
