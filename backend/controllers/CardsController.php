<?php
    require_once __DIR__ . '/../config/db.php'; 
    $conn = getConnection();
   require_once (__DIR__ . '/../model/CardsModel.php');
   
    class CardsController{

        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }


        public function showCards($brand_name){

                $cardsModel= new CardsModel($this->conn);
                $cards = $cardsModel->getShoes($brand_name);
                return $cards;
               

        }
        public function showCategory($category_name,$limit, $offset){
            $cardsModel = new CardsModel($this->conn);
            $categories=$cardsModel->getCategory($category_name,$limit,$offset);

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
        public function filterFetch(){

             header('Content-Type: application/json');
    
            $data = json_decode(file_get_contents("php://input"), true);
            
            $brand = $data['brand'] ?? null;
            $category = $data['category'] ?? null;
            $size = $data['size'] ?? null;
            $type = $data['type'] ?? null;


            $productsModel= new CardsModel($this->conn);
            $products = $productsModel->filterProducts($brand, $category, $size, $type);

            echo json_encode($products);


        }
        public function getShoeID($shoe_id){
            if($_SERVER['REQUEST_METHOD']==="POST"){
                $shoe_id=$_POST['shoe_id'];
            
            $cardsModel= new CardsModel($this->conn);
            $cards= $cardsModel->getShoeID($shoe_id);
            return $cards;
            }
        }



    }

?>