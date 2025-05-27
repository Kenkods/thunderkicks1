<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="/thunderkicks1/thunderkick-advdb/public/css/login.css">
</head>

<body>
    <div class="tk-container">
        <img src="/thunderkicks1/thunderkick-advdb/public/imgs/hehe.png" alt="" class="tk-logo">
        <div class="tk-input-group">
            <input type="text" id="username" name="username" placeholder="Username" required class="tk-input">
        </div>
        <div class="tk-input-group">
            <input type="password" id="password" name="password" placeholder="Password" required class="tk-input">
            <?php if (!empty($error)): ?>
                <span class="text-3xl">
                    <?= htmlspecialchars($error) ?>
                </span>
            <?php endif; ?>
        </div>
        <button type="submit" class="tk-login-button" id="log">Login</button>
    </div>
</body>

<script>
    document.getElementById('log').addEventListener("click", () => {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();

        const adminUser = "admin";
        const adminPass = "admin123";

        if (username === adminUser && password === adminPass) {
            alert("Login successful!");
            window.location.href = "/thunderkicks1/thunderkick-advdb/pages/dashboard/AdminDashboard.php";
        } else {
            alert("Invalid username or password.");
        }
    });
</script>

</html>