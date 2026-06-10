<?php
session_start();
include 'database/db_connect.php';

// User must be logged in to access dashboard
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Must be a seller
if (!isset($_SESSION['is_seller']) || $_SESSION['is_seller'] != 1) {
    header("Location: dashboard.php");
    exit();
}

// Get seller info
$user_id = $_SESSION['user_id'];

// Find the username and store name of a specific seller, using their user ID to look them up
$stmt = $conn->prepare("SELECT users.username, sellers.store_name, sellers.banner_image FROM users JOIN sellers ON users.user_id = sellers.user_id WHERE users.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $seller = $result->fetch_assoc();
    $username = $seller['username'];
    $store_name = $seller['store_name'];
} else {
    // If no seller info found, log out user
    session_destroy();
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="seller_dashboard_style.css">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="dashboard-topbar">
        <button class="back-btn" onclick="goHome()">
            <i class="fa fa-arrow-left"></i> Back
        </button>
    </div>

    <div class="seller-banner">
        <?php if (!empty($seller['banner_image'])): ?>
            <img src="uploads/banners/<?= htmlspecialchars($seller['banner_image']) ?>" alt="Store Banner"
                class="banner-image">
    
        <?php else: ?>
            <div class="default-banner">
                <h2><?= htmlspecialchars($seller['store_name']) ?></h2>
            </div>
        <?php endif; ?>

        <div class="banner-overlay">
            <h1><?= htmlspecialchars($seller['store_name']) ?></h1>
            <p>Welcome back, <?= htmlspecialchars($username)?></p>
        </div>
    </div>

    <div class="seller-dashboard">

        <h1>Welcome to <?= htmlspecialchars($seller['store_name']) ?>
        </h1>

        <p>
            Manage your store and products here.
        </p>

        <div class="dashboard-actions">
            <a href="seller_features/upload_products.php" class="dashboard-btn">
                Upload Products
            </a>

            <a href="seller_features/my_products.php" class="dashboard-btn">
                My Products
            </a>

            <a href="seller_features/view_my_orders.php" class="dashboard-btn">
                View Orders
            </a>

            <a href="seller_features/upload_banner.php" class="dashboard-btn">
                Change Banner
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="back_button.js"></script>
    
</body>
</html>