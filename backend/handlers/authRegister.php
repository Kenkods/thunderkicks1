<?php
    require_once '../controllers/userAuthController.php';

    $auth= new userAuthController();
    $auth->register();

?>