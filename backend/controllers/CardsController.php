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
            return $categories;

        }

    }



?>