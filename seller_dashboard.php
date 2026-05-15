<?php
session_start();
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';

// User must be logged in to access dashboard
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Must be a seller
if (!isset($_SESSION['is_seller']) || $_SESSION['is_seller'] != 1) {
    header("Location: dashboard.php");
    exit();
}

// Get seller info
$email = $_SESSION['email'];

// Find the username and store name of a specific seller, using their email address to look them up
$stmt = $conn->prepare("SELECT users.username, sellers.store_name FROM users JOIN sellers ON users.user_id = sellers.user_id WHERE users.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $seller = $result->fetch_assoc();
    $username = $seller['username'];
    $store_name = $seller['store_name'];
} else {
    // If no seller info found, log out user
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="seller_dashboard.css">
    <title>Seller Dashboard</title>
</head>
<body>
    <div class="seller-dashboard">

        <h1>Welcome, <?= htmlspecialchars($seller['store_name']) ?>
        </h1>

        <p>
            Manage your store and products here.
        </p>

        <div class="dashboard-actions">
            <a href="upload_product.php" class="dashboard-btn">
                Upload Products
            </a>

            <a href="my_products.php" class="dashboard-btn">
                My Products
            </a>

            <a href="orders.php" class="dashboard-btn">
                View Orders
            </a>
        </div>
    </div>
    
</body>
</html>