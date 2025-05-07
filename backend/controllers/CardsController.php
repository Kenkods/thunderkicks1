<?php
    require_once '/../model/CardsModel.php';

    class CardsController{
        public function showCards($brand_name){

                $cardsModel= new CardsModel();
               
                $cards []= $cardsModel->getShoes($brand_name);
                require __DIR__. '/../../Thunderkick-advdb/src/Landing.php';
               


        }

    }



?>