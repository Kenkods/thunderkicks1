<?php 
    require_once '../model/AuthModel.php';

    class userAuthController{
        public function register(){
            if($_SERVER['REQUEST_METHOD']==="POST"){
                $full_name=trim($_POST['full_name']);
                $email=trim($_POST['email']);
                $username=trim($_POST['username']);
                $password=$_POST['password'];
                $confirm_pass=$_POST['confirm_password'];



                if($password!==$confirm_pass){
                    echo "Password does not match";

                }
                else{
                    echo "Password is matched";

                    $userModel= new userModel();
                    $success=$userModel->createUser($full_name,$email,$username,$password);
                    if($success){
                        echo "User created successfully";

                    }
                    else{
                        echo "User creation failed";
                    }
                

            }

        }

    }
}
?>