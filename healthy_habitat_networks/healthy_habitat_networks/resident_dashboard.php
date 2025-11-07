<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'resident') {
    header('Location: login.php');
    exit();
}

$message = '';

// Handle voting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote'], $_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $vote = $_POST['vote'];
    $resident_id = $_SESSION['user_id'];

    $check = $pdo->prepare("SELECT * FROM votes WHERE product_id = ? AND resident_id = ?");
    $check->execute([$product_id, $resident_id]);

    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO votes (product_id, resident_id, vote) VALUES (?, ?, ?)");
        $stmt->execute([$product_id, $resident_id, $vote]);
        $message = "<div class='alert alert-success'>Vote recorded!</div>";
    } else {
        $message = "<div class='alert alert-warning'>You have already voted for this product.</div>";
    }
}

// Filters
$filter_category = $_GET['category'] ?? '';
$filter_price = $_GET['price'] ?? '';
$filter_location = $_GET['location'] ?? '';  // New location filter

// Fetch categories and locations
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();

$sql = "SELECT p.*, c.name AS category_name, l.location_name,
        (SELECT COUNT(*) FROM votes v WHERE v.product_id = p.id AND v.vote = 'yes') AS yes_votes,
        (SELECT COUNT(*) FROM votes v WHERE v.product_id = p.id AND v.vote = 'no') AS no_votes
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN locations l ON p.location_id = l.id
        WHERE p.available = 1";

$params = [];
if ($filter_category) {
    $sql .= " AND c.id = ?";
    $params[] = $filter_category;
}
if ($filter_price == 'under200') {
    $sql .= " AND p.price < 200";
}
if ($filter_location) {  // Apply location filter
    $sql .= " AND l.id = ?";
    $params[] = $filter_location;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resident Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="resident_bg">
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
<div class="d-flex" style="justify-content: end;
    display: flex
;">
    <span class="navbar-text me-3" style="    color: darkgray;">Welcome <?= htmlspecialchars($_SESSION['username']); ?> (Admin)</span>
</div>
<div class="container mt-4">
    <h3 class="text-center mb-4">Browse Products & Services</h3>
    <?= $message ?>

    <!-- Filter Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id']; ?>" <?= $filter_category == $cat['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <select name="price" class="form-select">
                <option value="">All Prices</option>
                <option value="under200" <?= $filter_price == 'under200' ? 'selected' : ''; ?>>Under £200</option>
            </select>
        </div>

        <!-- Location Filter -->
        <div class="col-md-4">
            <select name="location" class="form-select">
                <option value="">All Locations</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= $loc['id']; ?>" <?= $filter_location == $loc['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($loc['location_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- Products Table -->
    <div class="row">
        <?php if ($products): ?>
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
                            <span class="badge bg-success">Yes Votes: <?= $product['yes_votes']; ?></span>
                            <span class="badge bg-danger">No Votes: <?= $product['no_votes']; ?></span>
                            <form method="POST" class="mt-2">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                <button type="submit" name="vote" value="yes" class="btn btn-success btn-sm">Vote Yes</button>
                                <button type="submit" name="vote" value="no" class="btn btn-danger btn-sm">Vote No</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No products found.</p>
        <?php endif; ?>
    </div>
</div>
<!-- Footer -->
<div class="footer">
    <p style="color:white">&copy; 2025 Healthy Habitat. All Rights Reserved.</p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>
