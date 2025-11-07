<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'business') {
    header('Location: login.php');
    exit();
}

// Fetch products ranked by votes
$sql = "SELECT p.name, p.price,
        SUM(CASE WHEN v.vote = 'yes' THEN 1 ELSE 0 END) AS yes_votes
        FROM products p
        LEFT JOIN votes v ON p.id = v.product_id
        GROUP BY p.id
        ORDER BY yes_votes DESC";

$report = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Rankings</title>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link rel="stylesheet" href="css/styles.css">

</head>
<body>
    <h2>Product Rankings (by Yes Votes)</h2>
    <p><a href="business_dashboard.php">Back to Dashboard</a></p>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Product Name</th>
            <th>Price (£)</th>
            <th>Yes Votes</th>
        </tr>
        <?php foreach ($report as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['price']); ?></td>
                <td><?= $row['yes_votes'] ?? 0; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="js/scripts.js"></script>

</body>
</html>
