<?php
require_once __DIR__ . '/../config/db.php';
$conn = getConnection();
require_once(__DIR__ . '/../model/CardsModel.php');

class CardsController
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function showCards($brand_name)
    {

        $cardsModel = new CardsModel($this->conn);
        $cards = $cardsModel->getShoes($brand_name);
        return $cards;
    }
    public function showCategory($category_name, $limit, $offset)
    {
        $cardsModel = new CardsModel($this->conn);
        $categories = $cardsModel->getCategory($category_name, $limit, $offset);

        foreach ($categories as &$card) {
            if (!empty($card['sizes'])) {
                $sizesArr = explode(',', $card['sizes']);
                $sizes = [];
                foreach ($sizesArr as $sizeStock) {
                    list($size, $stock) = explode(':', $sizeStock);
                    $sizes[] = ['size' => $size, 'stock' => $stock];
                }
                $card['sizes'] = $sizes;
            } else {
                $card['sizes'] = [];
            }
        }
        unset($card);
        return $categories;
    }
    public function filterFetch()
    {

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $brand = $data['brand'] ?? null;
        $category = $data['category'] ?? null;
        $size = $data['size'] ?? null;
        $type = $data['type'] ?? null;


        $productsModel = new CardsModel($this->conn);
        $products = $productsModel->filterProducts($brand, $category, $size, $type);

        echo json_encode($products);
    }
    public function getShoeID($shoe_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $shoe_id = $_POST['shoe_id'];

            $cardsModel = new CardsModel($this->conn);
            $cards = $cardsModel->getShoeID($shoe_id);
         
            return $cards;
        }
    }

    public function addShoe(array $shoeData)
    {
        try {
            $required = ['name', 'brand', 'category', 'type', 'price', 'imagePath', 'sizes'];
            foreach ($required as $field) {
                if (empty($shoeData[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $model = new CardsModel($this->conn);
            $brand_id = $model->getBrandId($shoeData['brand']);
            $cat_id = $model->getCategoryId($shoeData['category']);
            $type_id = $model->getTypeId($shoeData['type']);

            if (!$brand_id || !$cat_id || !$type_id) {
                throw new Exception('Invalid brand, category, or type specified');
            }

            $price = (float)$shoeData['price'];
            if ($price <= 0) {
                throw new Exception('Price must be greater than 0');
            }

            if (!is_array($shoeData['sizes']) || empty($shoeData['sizes'])) {
                throw new Exception('At least one size must be specified');
            }

            $validSizes = [];
            foreach ($shoeData['sizes'] as $size => $stock) {
                $size = (int)$size;
                $stock = (int)$stock;

                if ($size < 1 || $size > 20) {
                    continue;
                }

                if ($stock < 0) {
                    $stock = 0;
                }

                $validSizes[$size] = $stock;
            }

            if (empty($validSizes)) {
                throw new Exception('No valid sizes were provided');
            }

            $shoe_id = $model->addShoe(
                $shoeData['name'],
                $brand_id,
                $cat_id,
                $price,
                $shoeData['imagePath'],
                $type_id
            );

            if (!$shoe_id) {
                throw new Exception('Failed to add shoe to database');
            }

            // Add sizes to database
            $sizeResult = $model->addSizes($shoe_id, $validSizes);

            if (!$sizeResult) {
                // Partial success - shoe added but sizes failed
                return [
                    'success' => false,
                    'message' => 'Shoe added but sizes failed to save',
                    'shoe_id' => $shoe_id
                ];
            }

            return [
                'success' => true,
                'message' => 'Shoe and sizes added successfully',
                'shoe_id' => $shoe_id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function displayAll(){
        $model = new CardsModel($this->conn);
        $result = $model->displayAll();
         foreach ($result as &$card) {
            if (!empty($card['sizes'])) {
                $sizesArr = explode(',', $card['sizes']);
                $sizes = [];
                foreach ($sizesArr as $sizeStock) {
                    list($size, $stock) = explode(':', $sizeStock);
                    $sizes[] = ['size' => $size, 'stock' => $stock];
                }
                $card['sizes'] = $sizes;
            } else {
                $card['sizes'] = [];
            }
        }

        unset($card);
        return $result;

    }




    public function viewTopProducts(){
        $model = new CardsModel($this->conn);
        $result = $model->getTop4Shoes();
        return $result;

    }
}
