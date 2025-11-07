<?php
session_start();
require 'config.php';

$message = '';

// Handle login request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check the username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // If user is found and password matches
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Save user role

        // Role-based redirection
        if ($_SESSION['role'] === 'resident') {
            header('Location: resident_dashboard.php');
        } elseif ($_SESSION['role'] === 'business') {
            header('Location: business_dashboard.php');
        } elseif ($_SESSION['role'] === 'admin') {
            header('Location: adminindex.php');
        } elseif ($_SESSION['role'] === 'council') {
            header('Location: council_dashboard.php'); // Redirect to council dashboard
        }
        exit();
    } else {
        // Display error message
        $message = "<div class='alert alert-danger'>Invalid username or password.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Healthy Habitat Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="login-bg">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #2e7d32;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/icon.webp" alt="Healthy Habitat Logo" style="width: 50px;">
        </a>
        <div class="collapse navbar-collapse justify-content-end" style="font-weight: bold;">
            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a href="register.php" class="btn btn-primary ms-3">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card p-4 rounded shadow">
        <h2 class="text-center mb-4 text-white">Login</h2>
        <?= $message ?>
        <form method="POST" class="needs-validation" novalidate>
        <div class="" style="margin-left: 0rem;">
            <iframe src="https://lottie.host/embed/8c8f145c-51fe-459c-9d74-8113aa0f51c8/J7nkov8pfi.lottie" width="300" height="300" style="border: none;"></iframe>
        </div>
            <div class="mb-3 position-relative">
                <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control rounded-end" placeholder="Username" required>
                    <div class="invalid-feedback">⚠ Please enter your username.</div>
                </div>
            </div>
            <div class="mb-3 position-relative">
                <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control rounded-end" placeholder="Password" required>
                    <div class="invalid-feedback">⚠ Please enter your password.</div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="register.php" class="text-white">Don't have an account? Register here.</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap 5 custom form validation
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
<!-- Footer -->
<div class="footer">
    <p style="color:white">&copy; 2025 Healthy Habitat. All Rights Reserved.</p>
</div>
</body>
</html>
