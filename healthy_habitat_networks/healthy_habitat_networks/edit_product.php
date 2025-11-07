<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'business') {
    header('Location: login.php');
    exit();
}

$product_id = $_GET['id'] ?? null;
$business_id = $_SESSION['user_id'];

if (!$product_id) {
    header('Location: business_dashboard.php');
    exit();
}

// Fetch product details
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND business_id = ?");
$stmt->execute([$product_id, $business_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: business_dashboard.php');
    exit();
}

// Handle update submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $certification = $_POST['certification'];

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, certification = ? WHERE id = ? AND business_id = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$name, $description, $price, $category_id, $certification, $product_id, $business_id]);
        $message = "<div class='alert alert-success'>Product updated successfully!</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}

// Fetch categories for the select dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="business-bg">

<div class="container mt-5">
    <div class="dashboard-card p-4">
        <h3 class="text-center mb-4 text-white">Edit Product</h3>
        <?= $message ?>
        <form method="POST" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label text-white">Product Name</label>
                <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>
                <div class="invalid-feedback">Enter product name.</div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label text-white">Description</label>
                <textarea name="description" class="form-control" id="description" required><?= htmlspecialchars($product['description']); ?></textarea>
                <div class="invalid-feedback">Enter a description.</div>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label text-white">Price (£)</label>
                <input type="number" name="price" class="form-control" id="price" step="0.01" value="<?= htmlspecialchars($product['price']); ?>" required>
                <div class="invalid-feedback">Enter a valid price.</div>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label text-white">Category</label>
                <select name="category_id" class="form-select" id="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Select a category.</div>
            </div>
            <div class="mb-3">
                <label for="certification" class="form-label text-white">Certification</label>
                <input type="text" name="certification" class="form-control" id="certification" value="<?= htmlspecialchars($product['certification']); ?>">
            </div>
            <button type="submit" class="btn btn-success w-100">Update Product</button>
            <a href="business_dashboard.php" class="btn btn-secondary w-100 mt-2">Back to Dashboard</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap validation script
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
