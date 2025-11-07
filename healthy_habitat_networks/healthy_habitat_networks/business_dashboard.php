<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'business') {
    header('Location: login.php');
    exit();
}

$message = '';

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $certification = $_POST['certification'];
    $business_id = $_SESSION['user_id'];
    $location_id = $_POST['location_id'];  // New location_id field

    $sql = "INSERT INTO products (business_id, category_id, location_id, name, description, price, certification) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$business_id, $category_id, $location_id, $name, $description, $price, $certification]);
        $message = "<div class='alert alert-success'>Product added successfully!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// Fetch locations for the location dropdown (can be managed by admin)
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();

// Fetch business's products
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, l.location_name FROM products p 
                       JOIN categories c ON p.category_id = c.id 
                       JOIN locations l ON p.location_id = l.id 
                       WHERE business_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Business Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="btn btn-outline-danger ms-3">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container" style="padding-top: 90px;">
    <h3 class="text-center mb-4">Add New Product</h3>
    <?= $message ?>

    <div class="card bd_card mb-4" style="    width: 50%;
    margin-left: 25%;">
        <!-- <div class="card-header text">Add New Product</div> -->
        <div class="card-body">
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label text">Product Name</label>
                    <input type="text" name="name" class="form-control form_text_bg" id="name" required>
                    <div class="invalid-feedback">Enter product name.</div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label text">Description</label>
                    <textarea name="description" class="form-control form_text_bg" id="description" required></textarea>
                    <div class="invalid-feedback">Enter a description.</div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label text">Price (£)</label>
                    <input type="number" name="price" class="form-control form_text_bg" id="price" step="0.01" required>
                    <div class="invalid-feedback">Enter a valid price.</div>
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label text">Category</label>
                    <select name="category_id" class="form-select form_text_bg" id="category_id" required>
                        <option value="">Choose...</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Select a category.</div>
                </div>
                <div class="mb-3">
                    <label for="location_id" class="form-label text">Location</label>
                    <select name="location_id" class="form-select form_text_bg" id="location_id" required>
                        <option value="">Select Location</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?= $loc['id']; ?>"><?= htmlspecialchars($loc['location_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Select a location.</div>
                </div>
                <div class="mb-3">
                    <label for="certification" class="form-label text">Certification</label>
                    <input type="text" name="certification" class="form-control form_text_bg" id="certification" pattern="[A-Za-z\s]{3,}">
                </div>
                <button type="submit" class="btn btn-primary w-100">Add Product</button>
            </form>
        </div>
    </div>
    <h3 class="text-center mb-4">Your Products</h3>
    <div class="row" style="    margin-bottom: 80px;">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($product['category_name']); ?></h6>
                    <p class="card-text"><?= htmlspecialchars($product['description']); ?></p>
                    <p>Price: £<?= htmlspecialchars($product['price']); ?></p>
                    <p>Certification: <?= htmlspecialchars($product['certification']); ?></p>
                    <p>Location: <?= htmlspecialchars($product['location_name']); ?></p> <!-- Display product location -->
                    <div class="d-flex justify-content-between">
                        <a href="edit_product.php?id=<?= $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_product.php?id=<?= $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap custom form validation
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
