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
        <form action="/thunderkicks1/thunderkick-advdb/public/index.php?page=login" method="POST">
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" class="login-button">Login</button>
            
            <a href="forgot.html" class="link-button">Forgot Password?</a>
            <a href="register" class="link-button">Register</a>
            <a href ="admin"     class="link-button">Admin Login</a>
        </form>
        
            
    </div>
</body>
</html>
