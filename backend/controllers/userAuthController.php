<?php 
    require_once __DIR__ . '/../config/db.php'; 
    $conn = getConnection();
    require_once(__DIR__ . '/../model/AuthModel.php');

    


    
    class userAuthController{
        private $conn;
        public function __construct($conn){
            $this->conn=$conn;


        }
        public function register(){
            if($_SERVER['REQUEST_METHOD']==="POST"){
                $full_name=trim($_POST['full_name']);
                $email=trim($_POST['email']);
                $username=trim($_POST['username']);
                $password=$_POST['password'];
                $confirm_pass=$_POST['confirm_password'];



                if($password!==$confirm_pass){
                    $error='Password does not match';

                }
                else{
                    $hashedpassword=password_hash($password,PASSWORD_DEFAULT);

                    $userModel= new userModel($this->conn);
                    $success=$userModel->createUser($full_name,$email,$username,$hashedpassword);
                    if($success){
                        echo "User created successfully";

                    }
                    else{
                        echo "User creation failed";
                    }
                

            }

        }

    }

    public function login(){
            if($_SERVER['REQUEST_METHOD']==="POST"){
                $username=trim($_POST['username']);
                $password=$_POST['password'];

               $userModel= new userModel($this->conn);
               $result=$userModel->login($username);
               if(password_verify(  $password,$result['password'] )){
                echo "Login successful Controller";
               }
               else{
                    echo "login Failure";
               }

                
            }


    }
}
?>