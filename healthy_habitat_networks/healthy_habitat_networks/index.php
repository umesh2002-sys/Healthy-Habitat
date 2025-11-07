<?php
session_start();
require 'config.php';

// Fetch all products from the database
$sql = "SELECT p.id, p.name, p.description, p.price, c.name AS category_name, l.location_name FROM products p 
        JOIN categories c ON p.category_id = c.id 
        JOIN locations l ON p.location_id = l.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Healthy Habitat Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="homepage-bg">

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

<!-- Main Content -->
<div class="container" style="    padding-top: 90px;">
    <div class="text-center text-white">
        <h1>Welcome to Healthy Habitat Network</h1>
        <p>Connecting health-conscious residents with sustainable businesses.</p>
    </div>

    <!-- Products Grid -->
    <div class="row g-3">

    <?php foreach ($products as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
            <div class="card custom-card">
                <img src="images/image.jpg" alt="<?= htmlspecialchars($product['name']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                    <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
                    <p><strong>£<?= htmlspecialchars($product['price']); ?></strong></p>
                    <a href="login.php?id=<?= $product['id']; ?>" class="btn btn-secondary">View Details</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


</div>

<!-- Footer -->
<div class="footer text-center mt-5">
    <p style="color:white">&copy; 2025 Healthy Habitat. All Rights Reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
