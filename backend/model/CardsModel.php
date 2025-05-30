<?php
class CardsModel
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getShoes($brand_name)
    {
        $query = "
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

    public function getCategory($category_name, $limit, $offset)
    {
        $query = "SELECT s.shoe_id, s.name, s.price, s.shoe_img, GROUP_CONCAT(CONCAT(sz.size, ':', sz.stock) SEPARATOR ', ') AS sizes
                FROM shoes s
                JOIN category c ON s.cat_id = c.cat_id
                JOIN sizes sz ON s.shoe_id = sz.shoe_id
                WHERE c.category_name = ?
                GROUP BY s.shoe_id
                LIMIT ? OFFSET ?";



        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare Failed: " . $this->conn->error);
        }
        $stmt->bind_param("sii", $category_name, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        $stmt->close();
        return $categories;
    }

    public function filterProducts($brand, $category, $size, $type)
    {
        $sql = "SELECT 
                shoes.*,
                brand.brand_name,
                category.category_name,
                type.shoe_type,
                GROUP_CONCAT(CONCAT(sizes.size, ':', sizes.stock) ORDER BY sizes.size SEPARATOR ',') AS all_sizes
            FROM shoes
            INNER JOIN brand ON shoes.brand_id = brand.brand_id
            INNER JOIN category ON shoes.cat_id = category.cat_id
            INNER JOIN type ON shoes.type_id = type.type_id
            INNER JOIN sizes ON shoes.shoe_id = sizes.shoe_id
            WHERE 1=1";

        $params = [];

        if ($brand) {
            $sql .= " AND brand.brand_name = ?";
            $params[] = $brand;
        }
        if ($category) {
            $sql .= " AND LOWER(category.category_name) = LOWER(?)";
            $params[] = $category;
        }
        if ($size) {
            $sql .= " AND shoes.shoe_id IN (
            SELECT shoe_id FROM sizes WHERE size = ?
        )";
            $params[] = $size;
        }
        if ($type) {
            $sql .= " AND type.shoe_type = ?";
            $params[] = $type;
        }

        $sql .= " GROUP BY shoes.shoe_id";

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
            $sizesRaw = explode(',', $row['all_sizes']);
            $row['sizes'] = array_map(function ($entry) {
                list($size, $stock) = explode(':', $entry);
                return ['size' => $size, 'stock' => (int)$stock];
            }, $sizesRaw);
            unset($row['all_sizes']);
            $products[] = $row;
        }

        return $products;
    }

    public function getShoeID($shoe_id)
    {
        $sql = "SELECT * FROM shoes WHERE shoe_id=?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $shoe_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $shoes = [];
        while ($row = $result->fetch_assoc()) {
            $shoes[] = $row;
        }
        return $shoes;
    }

    public function addShoe($name, $brand_id, $cat_id, $price, $imagePath, $type_id)
    {

        $query = "INSERT INTO shoes (name, brand_id, cat_id, price, shoe_img, type_id) 
              VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("siidsi", $name, $brand_id, $cat_id, $price, $imagePath, $type_id);
        $result = $stmt->execute();

        if ($result) {
            $shoe_id = $stmt->insert_id;
            $stmt->close();
            return $shoe_id;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function addSizes($shoe_id, $sizes)
    {
        $query = "INSERT INTO sizes (shoe_id, size, stock) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $success = true;
        foreach ($sizes as $size => $stock) {
            $stmt->bind_param("iii", $shoe_id, $size, $stock);
            if (!$stmt->execute()) {
                $success = false;
                break;
            }
        }
        $stmt->close();
        return $success;
    }


    public function getBrandId($brand_name)
    {
        $query = "SELECT brand_id FROM brand WHERE brand_name = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $brand_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['brand_id'] : null;
    }

    public function getCategoryId($category_name)
    {
        $query = "SELECT cat_id FROM category WHERE category_name = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['cat_id'] : null;
    }

    public function getTypeId($type_name)
    {
        $query = "SELECT type_id FROM type WHERE shoe_type = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $type_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['type_id'] : null;
    }

    public function displayAll()
    {
        $query = "SELECT s.shoe_id, s.name, s.price, s.shoe_img, GROUP_CONCAT(CONCAT(sz.size, ':', sz.stock) SEPARATOR ', ') AS sizes
                FROM shoes s
                JOIN sizes sz ON s.shoe_id = sz.shoe_id
                GROUP BY s.shoe_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }


    public function getTop4Shoes()
    {
        $sql = "SELECT * FROM top_4_shoes";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        $topShoes = [];

        while ($row = $result->fetch_assoc()) {
            $shoeId = $row['shoe_id'];
            if (!isset($topShoes[$shoeId])) {
                $topShoes[$shoeId] = [
                    'shoe_id' => $row['shoe_id'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'shoe_img' => $row['shoe_img'],
                    'sizes' => []
                ];
            }
            $topShoes[$shoeId]['sizes'][] = [
                'size_id' => $row['size_id'],
                'size' => $row['size']
            ];
        }

        $topShoes = array_values($topShoes);

        return $topShoes;
    }
}
