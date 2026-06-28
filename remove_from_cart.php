<?php 
session_start();
include 'database/db_connect.php';

// Remove product from cart
$id = $_GET['id'];
// If the product exists in the cart, remove it
if (isset($_SESSION['cart'][$id])) {
    // the unset() function is used to remove the specified product from the cart array in the session
    unset($_SESSION['cart'][$id]);
}

header("Location: cart.php");
exit();
?>