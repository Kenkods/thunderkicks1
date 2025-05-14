<?php 
    

    session_start();
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");  // For HTTP/1.0 compatibility
        header("Expires: 0");  // Prevents caching

       
    
    require_once __DIR__ . '/../../backend/config/config.php';
    require_once __DIR__. '/../../backend/route/web.php';
    



















    // 
    // require_once __DIR__ . '/../../backend/controllers/userAuthController.php';
    // require_once __DIR__ . '/../../backend/controllers/CardsController.php';


    // $cardController= new CardsController($conn);
    // $adidascards=$cardController->showCards('Adidas');
    

    
    // switch($request){
    //     case 'landing':
    //         require __DIR__ .  '../../pages/Landing/Landing.php';
    //         break;
    // }


    

    // $auth= new userAuthController($conn);
    // $auth->login();


   

    // $auth= new userAuthController($conn );
    // $auth->register();

    
?>