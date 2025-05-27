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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Status</h5>
                </div>
                <div class="modal-body " id="modalMessage">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="tk-container">
        <img src="/thunderkicks1/thunderkick-advdb/public/imgs/hehe.png" alt="" class="tk-logo">
        <form action="/thunderkicks1/thunderkick-advdb/public/index.php?page=login" method="POST">
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
            <button type="submit" class="tk-login-button">Login</button>
            <a href="forgot.html" class="tk-link-button">Forgot Password?</a>
            <a href="register" class="tk-link-button">Register</a>
            <a href="admin" class="tk-link-button">Admin Login</a>
        </form>
    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const params = new URLSearchParams(window.location.search);
        const status = params.get('status');
        const modalMessage = document.getElementById('modalMessage');
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

        if (status === 'success') {
            modalMessage.textContent = 'Login successful!';
            loginModal.show();
        } else if (status === 'failed') {
            modalMessage.textContent = 'Invalid username or password.';
            modalMessage.classList.add('text-danger');
            loginModal.show();
            setTimeout(() => {
                loginModal.hide();
            }, 3000);
        }
    </script>

</body>

</html>