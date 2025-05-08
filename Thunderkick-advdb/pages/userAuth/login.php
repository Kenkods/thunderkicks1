<?php 
   include_once(__DIR__ . '/../../../backend/config/db.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="/thunderkicks1/thunderkick-advdb/public/css/login.css">
</head>
<body>
    <div class="container">
        <img src="/thunderkicks1/thunderkick-advdb/public/imgs/hehe.png" alt="">
        <form action="/thunderkicks1/thunderkick-advdb/public/index.php" method="POST">
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" class="login-button">Login</button>
            
            <a href="forgot.html" class="link-button">Forgot Password?</a>
            <a href="register.php" class="link-button">Register</a>
        </form>
        
        <div class="social-icons">
            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_%282019%29.png" alt="Facebook"></a>
            <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram"></a>
        </div>         
    </div>
</body>
</html>
