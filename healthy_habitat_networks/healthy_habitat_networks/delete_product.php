<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'business') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $business_id = $_SESSION['user_id'];

    // Step 1: Delete votes for this product
    $stmt = $pdo->prepare("DELETE FROM votes WHERE product_id = ?");
    $stmt->execute([$product_id]);

    // Step 2: Delete the product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND business_id = ?");
    $stmt->execute([$product_id, $business_id]);
}

header('Location: business_dashboard.php');
exit();
?>
