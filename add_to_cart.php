<?php 
session_start();
// Add product to cart
include 'database/db_connect.php';

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

header("Location: cart.php");
exit();
?>