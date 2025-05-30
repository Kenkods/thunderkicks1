    <?php
    require_once __DIR__ . '/../controllers/CardsController.php';
    require_once __DIR__ . "/../controllers/cartsController.php";
    require_once __DIR__ . '/../controllers/userAuthController.php';
    require_once __DIR__ . '/../controllers/OrderController.php';

    $request = $_GET['page'] ?? 'landing';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['page'] ?? '') === 'filter-products') {
        // 
        $controller = new CardsController($conn);
        $controller->filterFetch();
        exit;
    }





    switch ($request) {
        case 'landing':
            require_once __DIR__ . '/../controllers/CardsController.php';
            $cards = new CardsController($conn);
            $topProducts = $cards->viewTopProducts();

            require BASE_PATH . '/pages/Landing/Landing.php';
            break;


        case 'login':
            if (isset($_SESSION['user'])) {
                header("Location: landing");
                exit;
            }

            // 
            $loginCont = new userAuthController($conn);
            $loginCont->login();


            require BASE_PATH . '/pages/userAuth/login.php';



            break;
        case 'register':
            if (isset($_SESSION['user'])) {
                header("Location: landing");
                exit;
            }
            // require_once __DIR__ . '/../controllers/userAuthController.php';
            $regCont = new userAuthController($conn);
            $regCont->register();
            require BASE_PATH . '/pages/userAuth/register.php';
            break;

        case 'logout':
            // require_once __DIR__ . '/../controllers/userAuthController.php';
            $logoutCont = new userAuthController($conn);
            $logoutCont->logout();

            break;
        case 'admin':
            // require_once __DIR__ . '/../controllers/userAuthController.php';
            $loginCont = new userAuthController($conn);
            $loginCont->login();
            require BASE_PATH . '/pages/userAuth/adminlog.php';

            break;
        case 'products':
            // require_once __DIR__ . '/../controllers/CardsController.php';
            $cards   = new CardsController($conn);
            // $adidascards = $cards->showCategory('Men', 10, 0);
            $adidascards = $cards->displayAll();
            require BASE_PATH . '/pages/shop/products.php';

            break;
        case 'products=kids':

            $cards   = new CardsController($conn);
            $adidascards = $cards->showCategory('Kids', 99, 0);
            require BASE_PATH . '/pages/shop/products.php';
            break;

        case 'products=women':

            $cards   = new CardsController($conn);
            $adidascards = $cards->showCategory('Women', 99, 0);
            require BASE_PATH . '/pages/shop/products.php';
            break;

        case 'products=men':

            $cards   = new CardsController($conn);
            $adidascards = $cards->showCategory('Men', 99, 0);
            require BASE_PATH . '/pages/shop/products.php';
            break;

        case 'addToCart':
            if (!isset($_SESSION['user'])) {
                header("Location: login");
                exit;
            }

            // require_once __DIR__ . '/../controllers/CardsController.php';
            // require_once __DIR__ . '/../controllers/cartsController.php';
            $cards = new CardsController($conn);
            $addCart = $cards->getShoeID(shoe_id: $_POST['shoe_id']);
            $cartsController = new cartsController($conn);
            $size = $_POST['size'];
            foreach ($addCart as $cartsAdded) {
                $shoe_id = $cartsAdded['shoe_id'];
                $price = $cartsAdded['price'];
                $quantity = 1;
                $user_id = $_SESSION['user']['user_id'];

                $cartsController->insertCartItems($user_id, $shoe_id, $quantity, $price, $size);
            }

            $_SESSION['added'] = [
                'success' => true
            ];



            echo json_encode([
                'success' => true,
                'cartItem' => $addCart
            ]);



            exit();


        case 'AdminDashboard':
            $orderController = new OrdersController($conn);
            $recentOrders = $orderController->getRecentOrders();
            require BASE_PATH . '/pages/dashboard/AdminDashboard.php';
            break;
        case 'adminlogin':
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username === "admin" && $password === "admin123") {
                $orderController = new OrdersController($conn);
                $recentOrders = $orderController->getRecentOrders();
                require BASE_PATH . '/pages/dashboard/AdminDashboard.php';
            } else {
                $error = "Invalid admin credentials";
                require BASE_PATH . '/pages/userAuth/adminlog.php';
            }
            break;


        case 'page=cart':
            $displayCart = new cartsController($conn);
            $orderCont = new OrdersController($conn);

            $carts = $displayCart->displayCarts($_SESSION['user']['user_id']);
            $receipt = $orderCont->orderReceipt();


            require BASE_PATH . '/pages/shop/cart.php';
            break;

        case 'order=success':
            $order = new cartsController($conn);
            $orderCont = new OrdersController($conn);
            $selected = $_POST['selected'];

            $displayCart = new cartsController($conn);
            $carts = $displayCart->displayCarts($_SESSION['user']['user_id']);
            $order->transferCartToOrder($_SESSION['user']['user_id'], $selected);
            header("Location: page=cart");
            exit();


        case 'admin-orders':
            require_once __DIR__ . '/../controllers/OrderController.php';
            $ordersController = new ordersController($conn);
            $orders = $ordersController->getAllOrders();
            require BASE_PATH . '/pages/dashboard/AdminDashboard.php';
            break;

        case 'order-details':
            if (!isset($_GET['id'])) {
                die("Missing order ID");
            }
            require_once __DIR__ . '/../controllers/OrderController.php';
            $ordersController = new ordersController($conn);
            $orderItems = $ordersController->getOrderItems((int)$_GET['id']);
            require BASE_PATH . '/pages/dashboard/AdminDashboard.php';
            break;

        case 'complete-order':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
                require_once __DIR__ . '/../controllers/OrderController.php';
                $orderController = new OrdersController($conn);
                $result = $orderController->updateOrderStatus($_POST['order_id'], 'Completed');

                if ($result) {
                    header("Location: AdminDashboard?success=Order+marked+as+completed");
                } else {
                    header("Location: AdminDashboard?error=Failed+to+update+order");
                }
                exit();
            }
            break;
    }
