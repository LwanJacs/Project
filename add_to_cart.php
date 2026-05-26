<?php 
session_start();
// Add product to cart
include 'database/db_connect.php';

//  User account must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "You must be logged in to add items to your cart.";
    $_SESSION['toastClass'] = "bg-warning";

    header("Location: browse.php");
    exit();
}
// Check if product ID is provided
if (!isset($_GET['prod_id'])) {
    header('Location: browse.php');
    exit();
}
$product_id = $_GET['prod_id'];

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    // If cart doesn't exist, create it as an empty array
    $_SESSION['cart'] = [];
}

// if item already exists, increase the quantity
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]++;
} else {
    $_SESSION['cart'][$product_id] = 1;
}

$_SESSION['message'] = "Product added to cart!";
$_SESSION['toastClass'] = "bg-success";

header("Location: browse.php");
exit();
?>