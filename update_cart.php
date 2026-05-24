<?php 
session_start();

if (isset($_GET['prod_id']) && isset($_GET['action'])) {

    $product_id = $_GET['prod_id'];
    $action = $_GET['action'];

    // Check if product exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        
        // Increase quantity
        if ($action === 'increase') {
            $_SESSION['cart'][$product_id]++;

        } elseif ($action === 'decrease') {

            $_SESSION['cart'][$product_id]--;

            //Remove item if quantity becomes 0
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
}

header("Location: cart.php");
exit();
?>
