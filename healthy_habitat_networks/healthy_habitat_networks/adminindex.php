<?php
session_start();
require 'config.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Count residents
$resident_stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'resident'");
$resident_count = $resident_stmt->fetchColumn();

// Count businesses
$business_stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'business'");
$business_count = $business_stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .main-container { margin-top: 50px; }
        .welcome-header { font-size: 2rem; font-weight: bold; color: white; }
        .footer { position: fixed; bottom: 0; left: 0; width: 100%; background-color: #2e7d32; text-align: center; padding: 10px; font-size: 14px; color: white; }
        <style>
    .small-card {
        height: 120px;  /* Control height */
        width: 100%;    /* Responsive width */
        border-radius: 12px;
    }
    .small-card .card-body {
        padding: 10px;
    }

    /* Push the cards to the bottom of the page */
    .dashboard-container {
        min-height: 80vh; /* Adjust height as needed */
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
</style>
    </style>
</head>
<body class="business-bg">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #2e7d32;">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="images/icon.webp" alt="Healthy Habitat Logo" style="width: 50px;">
        </a>
        <div class="collapse navbar-collapse justify-content-end" style="font-weight: bold;">
            <ul class="navbar-nav d-flex align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
                <li class="nav-item"><a href="logout.php" class="btn btn-outline-danger ms-3">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Welcome Message -->
<div class="d-flex justify-content-end  me-3" style="margin-top: 65px;">
    <span class="navbar-text" style="color: darkgray;">Welcome, <?= htmlspecialchars($_SESSION['username']); ?> (Admin)</span>
</div>

<!-- Main Content -->
<div class="container text-center main-container">
    <h2 class="welcome-header mb-4">Welcome Admin</h2>
    <p class="mb-4">Choose a dashboard to access:</p>
<!-- Dashboard Buttons -->
<div class="row">
        <div class="col-md-6 mb-3">
            <a href="admin_dashboard.php" class="btn btn-primary" style="background-color:rgb(47, 75, 105); padding: 15px; font-size: 18px; text-transform: uppercase; width: 100%;">Admin Dashboard</a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="council_dashboard.php" class="btn btn-secondary" style="background-color:rgb(43, 66, 101); padding: 15px; font-size: 18px; text-transform: uppercase; width: 100%;">Council Dashboard</a>
        </div>
    </div>
</div>
    <!-- Resident and Business Counts -->
    <div class="row mb-4 justify-content-center">
    <div class="col-4 col-md-3"> <!-- Decreased width -->
        <div class="card text-white bg-success mb-3 small-card"> <!-- Added small-card class -->
            <div class="card-body text-center">
                <h5 class="card-title">Residents</h5>
                <p class="card-text fs-4"><?= $resident_count ?></p>
            </div>
        </div>
    </div>
    <div class="col-4 col-md-3">
        <div class="card text-white bg-info mb-3 small-card">
            <div class="card-body text-center">
                <h5 class="card-title">Businesses</h5>
                <p class="card-text fs-4"><?= $business_count ?></p>
            </div>
        </div>
    </div>
</div>


<!-- Footer -->
<div class="footer">
    &copy; 2025 Healthy Habitat. All Rights Reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
