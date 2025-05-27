<?php
require_once __DIR__ . '/../config/db.php';
$conn = getConnection();
require_once(__DIR__ . '/../model/AuthModel.php');




    
    class userAuthController{
        private $conn;
        public function __construct($conn){
            $this->conn=$conn;
            $loggedin=false;



class userAuthController
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $confirm_pass = $_POST['confirm_password'];



            if ($password !== $confirm_pass) {
                $error = 'Password does not match';
            } else {
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

                $userModel = new userModel($this->conn);
                $success = $userModel->createUser($full_name, $email, $username, $hashedpassword);
                if ($success) {
                    header("Location: /thunderkicks1/Thunderkick-advdb/public/index.php?page=login");
                } else {
                    $error = "Username or email already exists";
                }
            }
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $userModel = new userModel($this->conn);
            $result = $userModel->login($username);
            if ($result && password_verify($password, $result['password'])) {
                $_SESSION['user'] = [
                    'username' => $result['username'],
                    'password' => $result['password']
                ];

               

                header("Location: /thunderkicks1/Thunderkick-advdb/public/index.php?page=landing");



                header("Location: /thunderkicks1/Thunderkick-advdb/public/index.php?page=landing&status=success");
            } else {
                header("Location: /thunderkicks1/Thunderkick-advdb/public/index.php?page=login&status=failed");
            }
        }
    }

    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            session_unset();
            session_destroy();
            header("Location: /thunderkicks1/Thunderkick-advdb/public/index.php?page=login");
        }
    }
}
