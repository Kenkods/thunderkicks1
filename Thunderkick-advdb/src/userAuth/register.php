<?php 
   include_once(__DIR__ . '/../../../backend/config/db.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="/thunderkicks1/thunderkick-advdb/public/css/register.css">
</head>
<body>
    <div class="container">
        <img src="/thunderkicks1/thunderkick-advdb/public/imgs/hehe.png" alt="">
        <form action="/thunderkicks1/backend/handlers/authRegister.php" method="POST">
            <div class="input-group">
                <input type="text" id="full-name" name="full_name" placeholder="Full Name" required>
            </div>
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-group">
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password"  required>  
            </div>

            <a href="login.php" class="forgot-password">Already have an account? Login</a>
            <button type="submit" class="login-button" >Register</button>
        </form>
    </div>
</body>
</html>
