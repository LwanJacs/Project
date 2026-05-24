<?php
session_start();
include 'database/db_connect.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("Your cart is empty.");
}

$user_id = $_SESSION['user_id'];
$total = 0;

// Calculate total
$stmt = $conn->prepare("SELECT * FROM products WHERE prod_id = ?");

// calculate total price
foreach ($cart as $product_id => $qty) {

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $total += $product['price'] * $qty;
    }
}

// Create order
$order_stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$order_stmt->bind_param("id", $user_id, $total);
if (!$order_stmt->execute()) {
    die("Failed to create order: ". $order_stmt->error);
};

// Getting a new order ID
$order_id = $conn->insert_id;

$item_stmt = $conn->prepare("INSERT INTO order_items (order_id, prod_id, quantity, price) VALUES (?, ?, ?, ?)");

// Insert order items
foreach ($cart as $product_id => $qty) {

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        
        $price = $product['price'];

        $item_stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
        $item_stmt->execute();

    }
}

// Clear cart
unset($_SESSION['cart']);

$_SESSION['message'] = "Order placed successfully!";
$_SESSION['toastClass'] = "bg-success";

header("Location: orders.php");
exit();
?>