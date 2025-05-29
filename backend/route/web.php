<?php
$request = $_GET['page'] ?? 'landing';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['page'] ?? '') === 'filter-products') {
    require_once __DIR__ . '/../controllers/CardsController.php';
    $controller = new CardsController($conn);
    $controller->filterFetch();
    exit;
}





switch ($request) {
    case 'landing':
        require_once __DIR__ . '/../controllers/CardsController.php';
        $cards = new CardsController($conn);
        $adidascards = $cards->showCategory('Men', 4, 0);
        require BASE_PATH . '/pages/Landing/Landing.php';

        return $adidascards;
    case 'login':
        if (isset($_SESSION['user'])) {
            header("Location: landing");
            exit;
        }

        require_once __DIR__ . '/../controllers/userAuthController.php';
        $loginCont = new userAuthController($conn);
        $loginCont->login();


        require BASE_PATH . '/pages/userAuth/login.php';



        break;
    case 'register':
        if (isset($_SESSION['user'])) {
            header("Location: landing");
            exit;
        }
        require_once __DIR__ . '/../controllers/userAuthController.php';
        $regCont = new userAuthController($conn);
        $regCont->register();
        require BASE_PATH . '/pages/userAuth/register.php';
        break;
    case 'logout':
        require_once __DIR__ . '/../controllers/userAuthController.php';
        $logoutCont = new userAuthController($conn);
        $logoutCont->logout();

        break;
    case 'admin':
        require_once __DIR__ . '/../controllers/userAuthController.php';
        $loginCont = new userAuthController($conn);
        $loginCont->login();
        require BASE_PATH . '/pages/userAuth/adminlog.php';

        break;
    case 'products':
        require_once __DIR__ . '/../controllers/CardsController.php';
        $cards   = new CardsController($conn);
        $adidascards = $cards->showCategory('Men', 10, 0);
        require BASE_PATH . '/pages/shop/products.php';

        break;
    case 'products=kids':
        require_once __DIR__ . '/../controllers/CardsController.php';
        $cards   = new CardsController($conn);
        $adidascards = $cards->showCategory('Kids', 4, 0);
        require BASE_PATH . '/pages/shop/products.php';
        break;
    case 'addToCart':



        if (!isset($_SESSION['user'])) {
            header("Location: login");
            exit;
        }

        require_once __DIR__ . '/../controllers/CardsController.php';
        require_once __DIR__ . '/../controllers/cartsController.php';
        $cards = new CardsController($conn);
        $addCart = $cards->getShoeID(shoe_id: $_POST['shoe_id']);
        $cartsController = new cartsController($conn);
        $size=$_POST['size'];
        foreach ($addCart as $cartsAdded) {
            $shoe_id = $cartsAdded['shoe_id'];
            $price = $cartsAdded['price'];
            $quantity = 1;
            $user_id = $_SESSION['user']['user_id'];

            $cartsController->insertCartItems($user_id, $shoe_id, $quantity, $price,$size);
        }

        $_SESSION['added'] = [
            'success' => true
        ];



        echo json_encode([
            'success' => true,
            'cartItem' => $addCart
        ]);



        exit();



    case 'AdminDashboard.php?success=1':
        require BASE_PATH . '/pages/dashboard/AdminDashboard.php';
        break;


    case 'page=cart':
        require_once __DIR__ . "/../controllers/cartsController.php";
        $displayCart = new cartsController($conn);
        $carts = $displayCart->displayCarts($_SESSION['user']['user_id']);


        require BASE_PATH . '/pages/shop/cart.php';
}
