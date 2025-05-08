<?php 
    $request=$_GET ['page'] ?? 'landing';
    require_once __DIR__ . '/../../backend/controllers/userAuthController.php';
    require_once __DIR__ . '/../../backend/controllers/CardsController.php';


    $cardController= new CardsController($conn);
    $adidascards=$cardController->showCards('Adidas');
    

    
    switch($request){
        case 'landing':
            require __DIR__ .  '../../pages/Landing/Landing.php';
            break;
    }


    

    $auth= new userAuthController($conn);
    $auth->login();


   

    $auth= new userAuthController($conn );
    $auth->register();

    
?>