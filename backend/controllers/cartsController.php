<?php
    require_once __DIR__ . '/../config/db.php';
    $conn = getConnection();
    require_once(__DIR__ . '/../model/CardsModel.php');
     require_once(__DIR__ . '/../model/cartsModel.php');

    class cartsController{
         private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


        public function insertCartItems($user_id, $shoe_id, $quantity, $price) {
    require_once(__DIR__ . '/../model/cartsModel.php');
    $model = new cartsModel($this->conn);
    $model->addorUpdateCart($user_id, $shoe_id, $quantity, $price);
    

}

    public function displayCarts($user_id){

        $cartModel= new cartsModel($this->conn);
       $carts= $cartModel->getCarts($user_id);
        return $carts;


    }


    }
?>