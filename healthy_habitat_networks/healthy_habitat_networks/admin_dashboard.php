<?php
session_start();
require 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $location_id = $_POST['location_id'];

    // Insert product with location_id into the products table
    $sql = "INSERT INTO products (name, description, location_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_name, $description, $location_id]);

    header('Location: admin_dashboard.php');
    exit();
}

// Handle Update Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $location_id = $_POST['location_id'];

    // Update product details
    $sql = "UPDATE products SET name = ?, description = ?, location_id = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_name, $description, $location_id, $product_id]);

    header('Location: admin_dashboard.php');
    exit();
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$product_id]);

    header('Location: admin_dashboard.php');
    exit();
}

// Fetch products with location and votes
$sql = "SELECT p.id, p.name AS product_name, p.description, l.location_name,
            (SELECT COUNT(*) FROM votes v WHERE v.product_id = p.id) AS total_votes
        FROM products p 
        JOIN locations l ON p.location_id = l.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();

// Fetch locations for dropdown
$location_sql = "SELECT * FROM locations";
$location_stmt = $pdo->prepare($location_sql);
$location_stmt->execute();
$locations = $location_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #2e7d32;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }
    </style>
<body class="business-bg" style="background: linear-gradient(135deg, #e0f7fa 0%, #f1f8e9 100%);">
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #2e7d32;">
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
                    <a href="adminindex.php" class="btn btn-primary ms-3">Back</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container py-5">
    <h2 class="mb-4 text-center">Admin Dashboard - Manage Products</h2>

    <!-- Add Product Form -->
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="col-md-4 mb-2">
                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>
            <div class="col-md-3 mb-2">
                <select name="location_id" class="form-select" required>
                    <option value="">Select Location</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= $loc['location_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button type="submit" name="add_product" class="btn btn-success w-100">Add</button>
            </div>
        </div>
    </form>

    <!-- Products Table -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Location</th>
                <th>Total Votes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $prod): ?>
                <tr>
                    <td><?= $prod['id'] ?></td>
                    <td><?= $prod['product_name'] ?></td>
                    <td><?= $prod['description'] ?></td>
                    <td><?= $prod['location_name'] ?></td>
                    <td><?= $prod['total_votes'] ?></td> <!-- Display total votes -->
                    <td>
                        <a href="?delete=<?= $prod['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $prod['id'] ?>">Edit</button>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $prod['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $prod['id'] ?>" aria-hidden="true">
                  <div class="modal-dialog">
                    <form method="POST">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editModalLabel<?= $prod['id'] ?>">Edit Product</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                          <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control" value="<?= $prod['product_name'] ?>" required>
                          </div>
                          <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required><?= $prod['description'] ?></textarea>
                          </div>
                          <div class="mb-3">
                            <label>Location</label>
                            <select name="location_id" class="form-select" required>
                              <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>" <?= $loc['location_name'] == $prod['location_name'] ? 'selected' : '' ?>>
                                  <?= $loc['location_name'] ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="update_product" class="btn btn-primary">Update</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Footer -->
<div class="footer">
    <p style="color:white">&copy; 2025 Healthy Habitat. All Rights Reserved.</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
