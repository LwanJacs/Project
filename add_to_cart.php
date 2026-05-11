<?php 
session_start();

$product_id = $_GET['cart'];

// Add to cart logic
if (!isset($_SESSION['cart'])) {
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