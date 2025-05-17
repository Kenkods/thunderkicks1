<?php 
    $request=$_GET ['page'] ?? 'landing';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['page'] ?? '') === 'filter-products') {
    require_once __DIR__ . '/../controllers/CardsController.php';
    $controller = new CardsController($conn);
    $controller->filterFetch(); 
    exit;
}

    



    switch($request){
        case 'landing':
            require_once __DIR__. '/../controllers/CardsController.php';
            $cards = new CardsController($conn);
            $adidascards=$cards->showCategory('Men',4,0);
            require BASE_PATH. '/pages/Landing/Landing.php';
             
            return $adidascards;
        case 'login':
            require_once __DIR__. '/../controllers/userAuthController.php';
            $loginCont= new userAuthController($conn);
            $loginCont->login();
            require BASE_PATH. '/pages/userAuth/login.php';
           
            break;
        case 'register':
            require_once __DIR__. '/../controllers/userAuthController.php';
            $regCont = new userAuthController($conn);
            $regCont->register();
            require BASE_PATH. '/pages/userAuth/register.php';
            break;
        case 'logout':
             require_once __DIR__. '/../controllers/userAuthController.php';
             $logoutCont = new userAuthController($conn);
             $logoutCont->logout();
             
            break;
        case 'admin':
             require_once __DIR__. '/../controllers/userAuthController.php';
            $loginCont= new userAuthController($conn);
            $loginCont->login();
            require BASE_PATH. '/pages/userAuth/adminlog.php';
           
            break;
        case 'products':
            require_once __DIR__. '/../controllers/CardsController.php';
            $cards   = new CardsController($conn);
            $adidascards=$cards->showCategory('Men',10,0);
            require BASE_PATH. '/pages/shop/products.php';
            
            break;
        case 'products=kids':
            require_once __DIR__. '/../controllers/CardsController.php';
            $cards   = new CardsController($conn);
            $adidascards=$cards->showCategory('Kids',4,0);
            require BASE_PATH. '/pages/shop/products.php';
            break;

           
           

        

            
         

    }
    

?>