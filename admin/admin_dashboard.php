<?php 
session_start();
include '../database/db_connect.php';

// Must be logged in 
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    header("Loaction: ../dashboard.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Check admin status
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['is_admin'] !=1) {
    die("Access denied.");
}


// Status queries
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];

$total_revenue = $conn->query("SELECT SUM(total_price) AS revenue FROM orders WHERE status = 'paid'")->fetch_assoc()['revenue'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">

        <h1>Admin Dashboard</h1>
        <div class="stats-grid">
            <div class="stat-card">
                <h2><?= $total_users ?></h2>
                <p>Users</p>
            </div>

            <div class="stat-card">
                <h2><?= $total_products ?></h2>
                <p>Products</p>
            </div>

            <div class="stat-card">
                <h2><?= $total_orders ?></h2>
                <p>Orders</p>
            </div>

            <div class="stat-card">
                <h2>R<?= number_format($total_revenue, 2) ?></h2>
                <p>Revenue</p>
            </div>
        </div>

        <div class="admin-actions">
            <a href="manage_users.php" class="admin-btn">
                Manage Users
            </a>
            <a href="manage_products.php" class="admin-btn">
                Manage Products
            </a>
            <a href="manage_orders.php" class="admin-btn">
                Manage Orders
            </a>
            <a href="manage_sellers.php" class="admin-btn">
                Manage sellers
            </a>
        </div>
    </div>
</body>
</html>
