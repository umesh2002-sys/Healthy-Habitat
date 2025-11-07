<?php
session_start();
require 'config.php';

$message = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

    // Check if the username already exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // If the username exists, show an error message
        $message = "<div class='alert alert-danger'>Username is already taken. Please choose another one.</div>";
    } else {
        // If username is available, proceed with registration
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([$username, $email, $password, $role]);
            $message = "<div class='alert alert-success'>Registration successful!</div>";
            header('Location: login.php'); // Redirect to login page after successful registration
            exit();
        } catch (PDOException $e) {
            // Handle any database errors
            $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Healthy Habitat Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="register-bg"> <!-- Add class for background -->
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
                    <a href="login.php" class="btn btn-primary ms-3">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-card p-4 rounded shadow">
    <h2 class="text-center mb-4 text-white">Register</h2>
        <?= $message ?>
        <form method="POST" class="needs-validation" novalidate>
            <div class="" style="margin-left: 0rem; ">
                <iframe src="https://lottie.host/embed/b88de5fd-0271-4347-a826-90b0e56b6601/NcJ99Z1XXe.lottie" width="300" height="250"style="border: none;"></iframe>
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
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control rounded-end" placeholder="Email" required>
                    <div class="invalid-feedback">⚠ Please enter a valid email address.</div>
                </div>
            </div>
            <div class="mb-3 position-relative">
                <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control rounded-end" placeholder="Password" required>
                    <div class="invalid-feedback">⚠ Please enter your password.</div>
                </div>
            </div>
            <div class="mb-3 position-relative">
                <div class="input-group has-validation">
                    <span class="input-group-text"><i class="bi bi-people-fill"></i></span>
                    <select name="role" class="form-select rounded-end" required>
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="resident">Resident</option>
                        <option value="business">Business</option>
                    </select>
                    <div class="invalid-feedback">⚠ Please select a role.</div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php" class="text-white">Already have an account? Login here.</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap 5 validation
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
