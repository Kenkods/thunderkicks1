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


        public function insertCartItems($user_id, $shoe_id, $quantity, $price,$size) {
    require_once(__DIR__ . '/../model/cartsModel.php');
    $model = new cartsModel($this->conn);
    $model->addorUpdateCart($user_id, $shoe_id, $quantity, $price,$size);
    

}

   

    public function displayCarts($user_id){

        $cartModel= new cartsModel($this->conn);
       $carts= $cartModel->getCarts($user_id);
        return $carts;


    }

     public function transferCartToOrder($userId, $selectedCartItemIds)
    {
        $cartModel= new cartsModel($this->conn);
        $selectedIdsString = implode(",", $selectedCartItemIds);

       
        $cartModel->transferCartToOrder($userId, $selectedIdsString);

        // You can add further logic here like redirecting or showing success message
    }




    }
?>