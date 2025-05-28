<?php
    class cartsModel{
        private $conn;

    public function __construct($conn) {  
        $this->conn = $conn;
    }


    public function insertCart($)  {

        $sql="INSERT INTO cart (user_id,shoe_id,total_amount) VALUES(?,?,?)";
        $stmt = $this->conn->prepare($sql);




        
    }


    }
?>